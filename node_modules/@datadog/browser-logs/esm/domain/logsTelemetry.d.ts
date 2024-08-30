import type { RawError, Observable, PageExitEvent } from '@datadog/browser-core';
import type { LogsConfiguration, LogsInitConfiguration } from './configuration';
import type { LogsSessionManager } from './logsSessionManager';
export declare function startLogsTelemetry(initConfiguration: LogsInitConfiguration, configuration: LogsConfiguration, reportError: (error: RawError) => void, pageExitObservable: Observable<PageExitEvent>, session: LogsSessionManager): {
    telemetry: import("@datadog/browser-core").Telemetry;
    stop: () => void;
};
