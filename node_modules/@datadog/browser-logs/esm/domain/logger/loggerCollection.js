var _a;
import { combine, ErrorSource, timeStampNow, originalConsoleMethods, globalConsole, ConsoleApiName, } from '@datadog/browser-core';
import { HandlerType } from '../logger';
import { isAuthorized, StatusType } from './isAuthorized';
export function startLoggerCollection(lifeCycle) {
    function handleLog(logsMessage, logger, handlingStack, savedCommonContext, savedDate) {
        var messageContext = combine(logger.getContext(), logsMessage.context);
        if (isAuthorized(logsMessage.status, HandlerType.console, logger)) {
            displayInConsole(logsMessage, messageContext);
        }
        if (isAuthorized(logsMessage.status, HandlerType.http, logger)) {
            var rawLogEventData = {
                rawLogsEvent: {
                    date: savedDate || timeStampNow(),
                    message: logsMessage.message,
                    status: logsMessage.status,
                    origin: ErrorSource.LOGGER,
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
    _a[StatusType.ok] = ConsoleApiName.debug,
    _a[StatusType.debug] = ConsoleApiName.debug,
    _a[StatusType.info] = ConsoleApiName.info,
    _a[StatusType.notice] = ConsoleApiName.info,
    _a[StatusType.warn] = ConsoleApiName.warn,
    _a[StatusType.error] = ConsoleApiName.error,
    _a[StatusType.critical] = ConsoleApiName.error,
    _a[StatusType.alert] = ConsoleApiName.error,
    _a[StatusType.emerg] = ConsoleApiName.error,
    _a);
function displayInConsole(_a, messageContext) {
    var status = _a.status, message = _a.message;
    originalConsoleMethods[loggerToConsoleApiName[status]].call(globalConsole, message, messageContext);
}
//# sourceMappingURL=loggerCollection.js.map