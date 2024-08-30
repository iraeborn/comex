import { ErrorSource, addTelemetryDebug } from '@datadog/browser-core';
import { StatusType } from './logger/isAuthorized';
export function startReportError(lifeCycle) {
    return function (error) {
        lifeCycle.notify(0 /* LifeCycleEventType.RAW_LOG_COLLECTED */, {
            rawLogsEvent: {
                message: error.message,
                date: error.startClocks.timeStamp,
                origin: ErrorSource.AGENT,
                status: StatusType.error,
            },
        });
        addTelemetryDebug('Error reported to customer', { 'error.message': error.message });
    };
}
//# sourceMappingURL=reportError.js.map