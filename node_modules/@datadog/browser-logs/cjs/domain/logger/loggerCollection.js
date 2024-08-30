"use strict";
var _a;
Object.defineProperty(exports, "__esModule", { value: true });
exports.startLoggerCollection = startLoggerCollection;
var browser_core_1 = require("@datadog/browser-core");
var logger_1 = require("../logger");
var isAuthorized_1 = require("./isAuthorized");
function startLoggerCollection(lifeCycle) {
    function handleLog(logsMessage, logger, handlingStack, savedCommonContext, savedDate) {
        var messageContext = (0, browser_core_1.combine)(logger.getContext(), logsMessage.context);
        if ((0, isAuthorized_1.isAuthorized)(logsMessage.status, logger_1.HandlerType.console, logger)) {
            displayInConsole(logsMessage, messageContext);
        }
        if ((0, isAuthorized_1.isAuthorized)(logsMessage.status, logger_1.HandlerType.http, logger)) {
            var rawLogEventData = {
                rawLogsEvent: {
                    date: savedDate || (0, browser_core_1.timeStampNow)(),
                    message: logsMessage.message,
                    status: logsMessage.status,
                    origin: browser_core_1.ErrorSource.LOGGER,
                },
                messageContext: messageContext,
                savedCommonContext: savedCommonContext,
            };
            if (handlingStack) {
                rawLogEventData.domainContext = { handlingStack: handlingStack };
            }
            lifeCycle.notify(0 /* LifeCycleEventType.RAW_LOG_COLLECTED */, rawLogEventData);
        }
    }
    return {
        handleLog: handleLog,
    };
}
var loggerToConsoleApiName = (_a = {},
    _a[isAuthorized_1.StatusType.ok] = browser_core_1.ConsoleApiName.debug,
    _a[isAuthorized_1.StatusType.debug] = browser_core_1.ConsoleApiName.debug,
    _a[isAuthorized_1.StatusType.info] = browser_core_1.ConsoleApiName.info,
    _a[isAuthorized_1.StatusType.notice] = browser_core_1.ConsoleApiName.info,
    _a[isAuthorized_1.StatusType.warn] = browser_core_1.ConsoleApiName.warn,
    _a[isAuthorized_1.StatusType.error] = browser_core_1.ConsoleApiName.error,
    _a[isAuthorized_1.StatusType.critical] = browser_core_1.ConsoleApiName.error,
    _a[isAuthorized_1.StatusType.alert] = browser_core_1.ConsoleApiName.error,
    _a[isAuthorized_1.StatusType.emerg] = browser_core_1.ConsoleApiName.error,
    _a);
function displayInConsole(_a, messageContext) {
    var status = _a.status, message = _a.message;
    browser_core_1.originalConsoleMethods[loggerToConsoleApiName[status]].call(browser_core_1.globalConsole, message, messageContext);
}
//# sourceMappingURL=loggerCollection.js.map