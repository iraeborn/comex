import type { TrackingConsentState } from '@datadog/browser-core';
import { type LogsConfiguration, type LogsInitConfiguration } from '../domain/configuration';
import type { CommonContext } from '../rawLogsEvent.types';
import type { Strategy } from './logsPublicApi';
import type { StartLogsResult } from './startLogs';
export declare function createPreStartStrategy(getCommonContext: () => CommonContext, trackingConsentState: TrackingConsentState, doStartLogs: (initConfiguration: LogsInitConfiguration, configuration: LogsConfiguration) => StartLogsResult): Strategy;
