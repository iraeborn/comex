import { AbstractLifeCycle } from '@datadog/browser-core';
import type { Context } from '@datadog/browser-core';
import type { LogsEvent } from '../logsEvent.types';
import type { CommonContext, RawLogsEvent } from '../rawLogsEvent.types';
import type { LogsEventDomainContext } from '../domainContext.types';
export declare const enum LifeCycleEventType {
    RAW_LOG_COLLECTED = 0,
    LOG_COLLECTED = 1
}
interface LifeCycleEventMap {
    [LifeCycleEventType.RAW_LOG_COLLECTED]: RawLogsEventCollectedData;
    [LifeCycleEventType.LOG_COLLECTED]: LogsEvent & Context;
}
export declare const LifeCycle: {
    new (): AbstractLifeCycle<LifeCycleEventMap>;
};
export type LifeCycle = AbstractLifeCycle<LifeCycleEventMap>;
export interface RawLogsEventCollectedData<E extends RawLogsEvent = RawLogsEvent> {
    rawLogsEvent: E;
    messageContext?: object;
    savedCommonContext?: CommonContext;
    domainContext?: LogsEventDomainContext<E['origin']>;
}
export {};
