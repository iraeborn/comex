import type { TrackingConsentState } from '@datadog/browser-core';
import type { LogsConfiguration, LogsInitConfiguration } from '../domain/configuration';
import type { CommonContext } from '../rawLogsEvent.types';
export type StartLogs = typeof startLogs;
export type StartLogsResult = ReturnType<StartLogs>;
export declare function startLogs(initConfiguration: LogsInitConfiguration, configuration: LogsConfiguration, getCommonContext: () => CommonContext, trackingConsentState: TrackingConsentState): {
    handleLog: (logsMessage: import("../domain/logger").LogsMessage, logger: import("../domain/logger").Logger, handlingStack?: string, savedCommonContext?: CommonContext, savedDate?: import("@datadog/browser-core").TimeStamp) => void;
    getInternalContext: (startTime?: number) => import("../domain/contexts/internalContext").InternalContext | undefined;
    stop: () => void;
};
