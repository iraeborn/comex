import type { SessionStoreStrategy, SessionStoreStrategyType } from './sessionStoreStrategy';
export declare function selectLocalStorageStrategy(): SessionStoreStrategyType | undefined;
export declare function initLocalStorageStrategy(): SessionStoreStrategy;
