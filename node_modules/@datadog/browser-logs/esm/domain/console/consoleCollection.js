var _a;
import { timeStampNow, ConsoleApiName, ErrorSource, initConsoleObservable } from '@datadog/browser-core';
import { StatusType } from '../logger/isAuthorized';
export var LogStatusForApi = (_a = {},
    _a[ConsoleApiName.log] = StatusType.info,
    _a[ConsoleApiName.debug] = StatusType.debug,
    _a[ConsoleApiName.info] = StatusType.info,
    _a[ConsoleApiName.warn] = StatusType.warn,
    _a[ConsoleApiName.error] = StatusType.error,
    _a);
export function startConsoleCollection(configuration, lifeCycle) {
    var consoleSubscription = initConsoleObservable(configuration.forwardConsoleLogs).subscribe(function (log) {
        var collectedData = {
            rawLogsEvent: {
                date: timeStampNow(),
                message: log.message,
                origin: ErrorSource.CONSOLE,
                error: log.api === ConsoleApiName.error
                    ? {
                        stack: log.stack,
                        fingerprint: log.fingerprint,
                        causes: log.causes,
                    }
                    : undefined,
                status: LogStatusForApi[log.api],
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