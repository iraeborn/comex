import { setTimeout, clearTimeout } from '../timer';
// use lodash API
export function throttle(fn, wait, options) {
    var needLeadingExecution = options && options.leading !== undefined ? options.leading : true;
    var needTrailingExecution = options && options.trailing !== undefined ? options.trailing : true;
    var inWaitPeriod = false;
    var pendingExecutionWithParameters;
    var pendingTimeoutId;
    return {
        throttled: function () {
            var parameters = [];
            for (var _i = 0; _i < arguments.length; _i++) {
                parameters[_i] = arguments[_i];
            }
            if (inWaitPeriod) {
                pendingExecutionWithParameters = parameters;
                return;
            }
            if (needLeadingExecution) {
                fn.apply(void 0, parameters);
            }
            else {
                pendingExecutionWithParameters = parameters;
            }
            inWaitPeriod = true;
            pendingTimeoutId = setTimeout(function () {
                if (needTrailingExecution && pendingExecutionWithParameters) {
                    fn.apply(void 0, pendingExecutionWithParameters);
                }
                inWaitPeriod = false;
                pendingExecutionWithParameters = undefined;
            }, wait);
        },
        cancel: function () {
            clearTimeout(pendingTimeoutId);
            inWaitPeriod = false;
            pendingExecutionWithParameters = undefined;
        },
    };
}
// eslint-disable-next-line @typescript-eslint/no-empty-function
export function noop() { }
//# sourceMappingURL=functionUtils.js.map