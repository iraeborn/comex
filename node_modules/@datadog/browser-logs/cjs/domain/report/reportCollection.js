"use strict";
var _a;
Object.defineProperty(exports, "__esModule", { value: true });
exports.startReportCollection = startReportCollection;
var browser_core_1 = require("@datadog/browser-core");
var isAuthorized_1 = require("../logger/isAuthorized");
var LogStatusForReport = (_a = {},
    _a[browser_core_1.RawReportType.cspViolation] = isAuthorized_1.StatusType.error,
    _a[browser_core_1.RawReportType.intervention] = isAuthorized_1.StatusType.error,
    _a[browser_core_1.RawReportType.deprecation] = isAuthorized_1.StatusType.warn,
    _a);
function startReportCollection(configuration, lifeCycle) {
    var reportSubscription = (0, browser_core_1.initReportObservable)(configuration, configuration.forwardReports).subscribe(function (report) {
        var message = report.message;
        var status = LogStatusForReport[report.type];
        var error;
        if (status === isAuthorized_1.StatusType.error) {
            error = {
                kind: report.subtype,
                stack: report.stack,
            };
        }
        else if (report.stack) {
            message += " Found in ".concat((0, browser_core_1.getFileFromStackTraceString)(report.stack));
        }
        lifeCycle.notify(0 /* LifeCycleEventType.RAW_LOG_COLLECTED */, {
            rawLogsEvent: {
                date: (0, browser_core_1.timeStampNow)(),
                message: message,
                origin: browser_core_1.ErrorSource.REPORT,
                error: error,
                status: status,
            },
        });
    });
    return {
        stop: function () {
            reportSubscription.unsubscribe();
        },
    };
}
//# sourceMappingURL=reportCollection.js.map