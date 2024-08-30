import { noop } from './utils/functionUtils';
/**
 * Object passed to the callback of an instrumented method call. See `instrumentMethod` for more
 * info.
 */
export type InstrumentedMethodCall<TARGET extends {
    [key: string]: any;
}, METHOD extends keyof TARGET> = {
    /**
     * The target object on which the method was called.
     */
    target: TARGET;
    /**
     * The parameters with which the method was called.
     *
     * Note: if needed, parameters can be mutated by the instrumentation
     */
    parameters: Parameters<TARGET[METHOD]>;
    /**
     * Registers a callback that will be called after the original method is called, with the method
     * result passed as argument.
     */
    onPostCall: (callback: PostCallCallback<TARGET, METHOD>) => void;
    /**
     * The stack trace of the method call.
     */
    handlingStack?: string;
};
type PostCallCallback<TARGET extends {
    [key: string]: any;
}, METHOD extends keyof TARGET> = (result: ReturnType<TARGET[METHOD]>) => void;
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
export declare function instrumentMethod<TARGET extends {
    [key: string]: any;
}, METHOD extends keyof TARGET & string>(targetPrototype: TARGET, method: METHOD, onPreCall: (this: null, callInfos: InstrumentedMethodCall<TARGET, METHOD>) => void, { computeHandlingStack }?: {
    computeHandlingStack?: boolean;
}): {
    stop: typeof noop;
};
export declare function instrumentSetter<TARGET extends {
    [key: string]: any;
}, PROPERTY extends keyof TARGET>(targetPrototype: TARGET, property: PROPERTY, after: (target: TARGET, value: TARGET[PROPERTY]) => void): {
    stop: typeof noop;
};
export {};
