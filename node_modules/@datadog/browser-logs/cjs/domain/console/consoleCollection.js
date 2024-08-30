"use strict";
var _a;
Object.defineProperty(exports, "__esModule", { value: true });
exports.LogStatusForApi = void 0;
exports.startConsoleCollection = startConsoleCollection;
var browser_core_1 = require("@datadog/browser-core");
var isAuthorized_1 = require("../logger/isAuthorized");
exports.LogStatusForApi = (_a = {},
    _a[browser_core_1.ConsoleApiName.log] = isAuthorized_1.StatusType.info,
    _a[browser_core_1.ConsoleApiName.debug] = isAuthorized_1.StatusType.debug,
    _a[browser_core_1.ConsoleApiName.info] = isAuthorized_1.StatusType.info,
    _a[browser_core_1.ConsoleApiName.warn] = isAuthorized_1.StatusType.warn,
    _a[browser_core_1.ConsoleApiName.error] = isAuthorized_1.StatusType.error,
    _a);
function startConsoleCollection(configuration, lifeCycle) {
    var consoleSubscription = (0, browser_core_1.initConsoleObservable)(configuration.forwardConsoleLogs).subscribe(function (log) {
        var collectedData = {
            rawLogsEvent: {
                date: (0, browser_core_1.timeStampNow)(),
                message: log.message,
                origin: browser_core_1.ErrorSource.CONSOLE,
                error: log.api === browser_core_1.ConsoleApiName.error
                    ? {
                        stack: log.stack,
                        fingerprint: log.fingerprint,
                        causes: log.causes,
                    }
                    : undefined,
                status: exports.LogStatusForApi[log.api],
            },
            domainContext: {
                handlingStack: log.handlingStack,
            },
        };
        lifeCycle.notify(0 /* LifeCycleEventType.RAW_LOG_COLLECTED */, collectedData);
    });
    return {
        stop: function () {
            consoleSubscription.unsubscribe();
        },
    };
}
//# sourceMappingURL=consoleCollection.js.map