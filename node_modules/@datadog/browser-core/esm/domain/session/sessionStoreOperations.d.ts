import type { SessionStoreStrategy } from './storeStrategies/sessionStoreStrategy';
import type { SessionState } from './sessionState';
type Operations = {
    process: (sessionState: SessionState) => SessionState | undefined;
    after?: (sessionState: SessionState) => void;
};
export declare const LOCK_RETRY_DELAY = 10;
export declare const LOCK_MAX_TRIES = 100;
export declare function processSessionStoreOperations(operations: Operations, sessionStoreStrategy: SessionStoreStrategy, numberOfRetries?: number): void;
export {};
