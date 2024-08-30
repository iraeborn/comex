import { callMonitored } from '../monitor';
import { noop } from '../utils/functionUtils';
import { computeStackTrace } from './computeStackTrace';
/**
 * Creates a stacktrace without SDK internal frames.
 * Constraints:
 * - Has to be called at the utmost position of the call stack.
 * - No monitored function should encapsulate it, that is why we need to use callMonitored inside it.
 */
export function createHandlingStack() {
    /**
     * Skip the two internal frames:
     * - SDK API (console.error, ...)
     * - this function
     * in order to keep only the user calls
     */
    var internalFramesToSkip = 2;
    var error = new Error();
    var formattedStack;
    // IE needs to throw the error to fill in the stack trace
    if (!error.stack) {
        try {
            throw error;
        }
        catch (e) {
            noop();
        }
    }
    callMonitored(function () {
        var stackTrace = computeStackTrace(error);
        stackTrace.stack = stackTrace.stack.slice(internalFramesToSkip);
        formattedStack = toStackTraceString(stackTrace);
    });
    return formattedStack;
}
export function toStackTraceString(stack) {
    var result = formatErrorMessage(stack);
    stack.stack.forEach(function (frame) {
        var func = frame.func === '?' ? '<anonymous>' : frame.func;
        var args = frame.args && frame.args.length > 0 ? "(".concat(frame.args.join(', '), ")") : '';
        var line = frame.line ? ":".concat(frame.line) : '';
        var column = frame.line && frame.column ? ":".concat(frame.column) : '';
        result += "\n  at ".concat(func).concat(args, " @ ").concat(frame.url).concat(line).concat(column);
    });
    return result;
}
export function formatErrorMessage(stack) {
    return "".concat(stack.name || 'Error', ": ").concat(stack.message);
}
//# sourceMappingURL=handlingStack.js.map