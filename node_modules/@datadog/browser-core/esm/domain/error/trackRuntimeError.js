import { instrumentMethod } from '../../tools/instrumentMethod';
import { clocksNow } from '../../tools/utils/timeUtils';
import { computeStackTrace, computeStackTraceFromOnErrorMessage } from '../../tools/stackTrace/computeStackTrace';
import { computeRawError } from './error';
import { ErrorSource } from './error.types';
export function trackRuntimeError(errorObservable) {
    var handleRuntimeError = function (stackTrace, originalError) {
        var rawError = computeRawError({
            stackTrace: stackTrace,
            originalError: originalError,
            startClocks: clocksNow(),
            nonErrorPrefix: "Uncaught" /* NonErrorPrefix.UNCAUGHT */,
            source: ErrorSource.SOURCE,
            handling: "unhandled" /* ErrorHandling.UNHANDLED */,
        });
        errorObservable.notify(rawError);
    };
    var stopInstrumentingOnError = instrumentOnError(handleRuntimeError).stop;
    var stopInstrumentingOnUnhandledRejection = instrumentUnhandledRejection(handleRuntimeError).stop;
    return {
        stop: function () {
            stopInstrumentingOnError();
            stopInstrumentingOnUnhandledRejection();
        },
    };
}
export function instrumentOnError(callback) {
    return instrumentMethod(window, 'onerror', function (_a) {
        var _b = _a.parameters, messageObj = _b[0], url = _b[1], line = _b[2], column = _b[3], errorObj = _b[4];
        var stackTrace;
        if (errorObj instanceof Error) {
            stackTrace = computeStackTrace(errorObj);
        }
        else {
            stackTrace = computeStackTraceFromOnErrorMessage(messageObj, url, line, column);
        }
        callback(stackTrace, errorObj !== null && errorObj !== void 0 ? errorObj : messageObj);
    });
}
export function instrumentUnhandledRejection(callback) {
    return instrumentMethod(window, 'onunhandledrejection', function (_a) {
        var e = _a.parameters[0];
        var reason = e.reason || 'Empty reason';
        var stack = computeStackTrace(reason);
        callback(stack, reason);
    });
}
//# sourceMappingURL=trackRuntimeError.js.map