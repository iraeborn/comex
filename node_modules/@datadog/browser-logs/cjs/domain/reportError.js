"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.startReportError = startReportError;
var browser_core_1 = require("@datadog/browser-core");
var isAuthorized_1 = require("./logger/isAuthorized");
function startReportError(lifeCycle) {
    return function (error) {
        lifeCycle.notify(0 /* LifeCycleEventType.RAW_LOG_COLLECTED */, {
            rawLogsEvent: {
                message: error.message,
                date: error.startClocks.timeStamp,
                origin: browser_core_1.ErrorSource.AGENT,
                status: isAuthorized_1.StatusType.error,
            },
        });
        (0, browser_core_1.addTelemetryDebug)('Error reported to customer', { 'error.message': error.message });
    };
}
//# sourceMappingURL=reportError.js.map