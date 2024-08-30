import { setTimeout } from './timer';
import { callMonitored } from './monitor';
import { noop } from './utils/functionUtils';
import { arrayFrom, startsWith } from './utils/polyfills';
import { createHandlingStack } from './stackTrace/handlingStack';
/**
 * Instruments a method on a object, calling the given callback before the original method is
 * invoked. The callback receives an object with information about the method call.
 *
 * This function makes sure that we are "good citizens" regarding third party instrumentations: when
 * removing the instrumentation, the original method is usually restored, but if a third party
 * instrumentation was set after ours, we keep it in place and just replace our instrumentation with
 * a noop.
 *
 * Note: it is generally better to instrument methods that are "owned" by the object instead of ones
 * that are inherited from the prototype chain. Example:
 * * do:    `instrumentMethod(Array.prototype, 'push', ...)`
 * * don't: `instrumentMethod([], 'push', ...)`
 *
 * This method is also used to set event handler properties (ex: window.onerror = ...), as it has
 * the same requirements as instrumenting a method:
 * * if the event handler is already set by a third party, we need to call it and not just blindly
 * override it.
 * * if the event handler is set by a third party after us, we need to keep it in place when
 * removing ours.
 *
 * @example
 *
 *  instrumentMethod(window, 'fetch', ({ target, parameters, onPostCall }) => {
 *    console.log('Before calling fetch on', target, 'with parameters', parameters)
 *
 *    onPostCall((result) => {
 *      console.log('After fetch calling on', target, 'with parameters', parameters, 'and result', result)
 *    })
 *  })
 */
export function instrumentMethod(targetPrototype, method, onPreCall, _a) {
    var _b = _a === void 0 ? {} : _a, computeHandlingStack = _b.computeHandlingStack;
    var original = targetPrototype[method];
    if (typeof original !== 'function') {
        if (startsWith(method, 'on')) {
            original = noop;
        }
        else {
            return { stop: noop };
        }
    }
    var stopped = false;
    var instrumentation = function () {
        if (stopped) {
            // eslint-disable-next-line @typescript-eslint/no-unsafe-return, @typescript-eslint/no-unsafe-call
            return original.apply(this, arguments);
        }
        var parameters = arrayFrom(arguments);
        var postCallCallback;
        callMonitored(onPreCall, null, [
            {
                target: this,
                parameters: parameters,
                onPostCall: function (callback) {
                    postCallCallback = callback;
                },
                handlingStack: computeHandlingStack ? createHandlingStack() : undefined,
            },
        ]);
        // eslint-disable-next-line @typescript-eslint/no-unsafe-call
        var result = original.apply(this, parameters);
        if (postCallCallback) {
            callMonitored(postCallCallback, null, [result]);
        }
        // eslint-disable-next-line @typescript-eslint/no-unsafe-return
        return result;
    };
    targetPrototype[method] = instrumentation;
    return {
        stop: function () {
            stopped = true;
            // If the instrumentation has been removed by a third party, keep the last one
            if (targetPrototype[method] === instrumentation) {
                targetPrototype[method] = original;
            }
        },
    };
}
export function instrumentSetter(targetPrototype, property, after) {
    var originalDescriptor = Object.getOwnPropertyDescriptor(targetPrototype, property);
    if (!originalDescriptor || !originalDescriptor.set || !originalDescriptor.configurable) {
        return { stop: noop };
    }
    var stoppedInstrumentation = noop;
    var instrumentation = function (target, value) {
        // put hooked setter into event loop to avoid of set latency
        setTimeout(function () {
            if (instrumentation !== stoppedInstrumentation) {
                after(target, value);
            }
        }, 0);
    };
    var instrumentationWrapper = function (value) {
        originalDescriptor.set.call(this, value);
        instrumentation(this, value);
    };
    Object.defineProperty(targetPrototype, property, {
        set: instrumentationWrapper,
    });
    return {
        stop: function () {
            var _a;
            if (((_a = Object.getOwnPropertyDescriptor(targetPrototype, property)) === null || _a === void 0 ? void 0 : _a.set) === instrumentationWrapper) {
                Object.defineProperty(targetPrototype, property, originalDescriptor);
            }
            instrumentation = stoppedInstrumentation;
        },
    };
}
//# sourceMappingURL=instrumentMethod.js.map