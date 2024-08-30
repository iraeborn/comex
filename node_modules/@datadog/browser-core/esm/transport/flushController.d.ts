import type { PageExitEvent, PageExitReason } from '../browser/pageExitObservable';
import { Observable } from '../tools/observable';
import type { Duration } from '../tools/utils/timeUtils';
export type FlushReason = PageExitReason | 'duration_limit' | 'bytes_limit' | 'messages_limit' | 'session_expire';
export type FlushController = ReturnType<typeof createFlushController>;
export interface FlushEvent {
    reason: FlushReason;
    bytesCount: number;
    messagesCount: number;
}
interface FlushControllerOptions {
    messagesLimit: number;
    bytesLimit: number;
    durationLimit: Duration;
    pageExitObservable: Observable<PageExitEvent>;
    sessionExpireObservable: Observable<void>;
}
/**
 * Returns a "flush controller", responsible of notifying when flushing a pool of pending data needs
 * to happen. The implementation is designed to support both synchronous and asynchronous usages,
 * but relies on invariants described in each method documentation to keep a coherent state.
 */
export declare function createFlushController({ messagesLimit, bytesLimit, durationLimit, pageExitObservable, sessionExpireObservable, }: FlushControllerOptions): {
    flushObservable: Observable<FlushEvent>;
    readonly messagesCount: number;
    /**
     * Notifies that a message will be added to a pool of pending messages waiting to be flushed.
     *
     * This function needs to be called synchronously, right before adding the message, so no flush
     * event can happen after `notifyBeforeAddMessage` and before adding the message.
     *
     * @param estimatedMessageBytesCount: an estimation of the message bytes count once it is
     * actually added.
     */
    notifyBeforeAddMessage(estimatedMessageBytesCount: number): void;
    /**
     * Notifies that a message *was* added to a pool of pending messages waiting to be flushed.
     *
     * This function can be called asynchronously after the message was added, but in this case it
     * should not be called if a flush event occurred in between.
     *
     * @param messageBytesCountDiff: the difference between the estimated message bytes count and
     * its actual bytes count once added to the pool.
     */
    notifyAfterAddMessage(messageBytesCountDiff?: number): void;
    /**
     * Notifies that a message was removed from a pool of pending messages waiting to be flushed.
     *
     * This function needs to be called synchronously, right after removing the message, so no flush
     * event can happen after removing the message and before `notifyAfterRemoveMessage`.
     *
     * @param messageBytesCount: the message bytes count that was added to the pool. Should
     * correspond to the sum of bytes counts passed to `notifyBeforeAddMessage` and
     * `notifyAfterAddMessage`.
     */
    notifyAfterRemoveMessage(messageBytesCount: number): void;
};
export {};
