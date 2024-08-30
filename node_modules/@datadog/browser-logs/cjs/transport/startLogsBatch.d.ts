import type { Context, Observable, PageExitEvent, RawError } from '@datadog/browser-core';
import type { LogsConfiguration } from '../domain/configuration';
import type { LifeCycle } from '../domain/lifeCycle';
import type { LogsSessionManager } from '../domain/logsSessionManager';
export declare function startLogsBatch(configuration: LogsConfiguration, lifeCycle: LifeCycle, reportError: (error: RawError) => void, pageExitObservable: Observable<PageExitEvent>, session: LogsSessionManager): {
    flushObservable: Observable<import("@datadog/browser-core").FlushEvent>;
    add(message: Context, replicated?: boolean): void;
    upsert: (message: Context, key: string) => void;
    stop: () => void;
};
