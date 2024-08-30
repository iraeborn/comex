import type { ContextManager } from '@datadog/browser-core';
import type { CommonContext } from '../../rawLogsEvent.types';
export declare function buildCommonContext(globalContextManager: ContextManager, userContextManager: ContextManager): CommonContext;
