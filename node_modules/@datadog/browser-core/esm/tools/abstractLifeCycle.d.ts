import type { Subscription } from './observable';
/**
 * Type helper to extract event types that have "void" data. This allows to call `notify` without a
 * second argument. Ex:
 *
 * ```
 * interface EventMap {
 *   foo: void
 * }
 * const LifeCycle = AbstractLifeCycle<EventMap>
 * new LifeCycle().notify('foo')
 * ```
 */
type EventTypesWithoutData<EventMap> = {
    [K in keyof EventMap]: EventMap[K] extends void ? K : never;
}[keyof EventMap];
export declare class AbstractLifeCycle<EventMap> {
    private callbacks;
    notify<EventType extends EventTypesWithoutData<EventMap>>(eventType: EventType): void;
    notify<EventType extends keyof EventMap>(eventType: EventType, data: EventMap[EventType]): void;
    subscribe<EventType extends keyof EventMap>(eventType: EventType, callback: (data: EventMap[EventType]) => void): Subscription;
}
export {};
