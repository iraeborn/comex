"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.trackRuntimeError = trackRuntimeError;
exports.instrumentOnError = instrumentOnError;
exports.instrumentUnhandledRejection = instrumentUnhandledRejection;
var instrumentMethod_1 = require("../../tools/instrumentMethod");
var timeUtils_1 = require("../../tools/utils/timeUtils");
var computeStackTrace_1 = require("../../tools/stackTrace/computeStackTrace");
var error_1 = require("./error");
var error_types_1 = require("./error.types");
function trackRuntimeError(errorObservable) {
    var handleRuntimeError = function (stackTrace, originalError) {
        var rawError = (0, error_1.computeRawError)({
            stackTrace: stackTrace,
            originalError: originalError,
            startClocks: (0, timeUtils_1.clocksNow)(),
            nonErrorPrefix: "Uncaught" /* NonErrorPrefix.UNCAUGHT */,
            source: error_types_1.ErrorSource.SOURCE,
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
function instrumentOnError(callback) {
    return (0, instrumentMethod_1.instrumentMethod)(window, 'onerror', function (_a) {
        var _b = _a.parameters, messageObj = _b[0], url = _b[1], line = _b[2], column = _b[3], errorObj = _b[4];
        var stackTrace;
        if (errorObj instanceof Error) {
            stackTrace = (0, computeStackTrace_1.computeStackTrace)(errorObj);
        }
        else {
            stackTrace = (0, computeStackTrace_1.computeStackTraceFromOnErrorMessage)(messageObj, url, line, column);
        }
        callback(stackTrace, errorObj !== null && errorObj !== void 0 ? errorObj : messageObj);
    });
}
function instrumentUnhandledRejection(callback) {
    return (0, instrumentMethod_1.instrumentMethod)(window, 'onunhandledrejection', function (_a) {
        var e = _a.parameters[0];
        var reason = e.reason || 'Empty reason';
        var stack = (0, computeStackTrace_1.computeStackTrace)(reason);
        callback(stack, reason);
    });
}
//# sourceMappingURL=trackRuntimeError.js.map