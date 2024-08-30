import type { RawError } from '@datadog/browser-core';
import type { CommonContext } from '../rawLogsEvent.types';
import type { LogsConfiguration } from './configuration';
import type { LifeCycle } from './lifeCycle';
import type { LogsSessionManager } from './logsSessionManager';
export declare function startLogsAssembly(sessionManager: LogsSessionManager, configuration: LogsConfiguration, lifeCycle: LifeCycle, getCommonContext: () => CommonContext, reportError: (error: RawError) => void): void;
