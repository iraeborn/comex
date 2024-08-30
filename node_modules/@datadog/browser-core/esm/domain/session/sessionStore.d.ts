import { Observable } from '../../tools/observable';
import type { InitConfiguration } from '../configuration';
import type { SessionStoreStrategyType } from './storeStrategies/sessionStoreStrategy';
import type { SessionState } from './sessionState';
export interface SessionStore {
    expandOrRenewSession: () => void;
    expandSession: () => void;
    getSession: () => SessionState;
    restartSession: () => void;
    renewObservable: Observable<void>;
    expireObservable: Observable<void>;
    sessionStateUpdateObservable: Observable<{
        previousState: SessionState;
        newState: SessionState;
    }>;
    expire: () => void;
    stop: () => void;
    updateSessionState: (state: Partial<SessionState>) => void;
}
/**
 * Every second, the storage will be polled to check for any change that can occur
 * to the session state in another browser tab, or another window.
 * This value has been determined from our previous cookie-only implementation.
 */
export declare const STORAGE_POLL_DELAY = 1000;
/**
 * Checks if cookies are available as the preferred storage
 * Else, checks if LocalStorage is allowed and available
 */
export declare function selectSessionStoreStrategyType(initConfiguration: InitConfiguration): SessionStoreStrategyType | undefined;
/**
 * Different session concepts:
 * - tracked, the session has an id and is updated along the user navigation
 * - not tracked, the session does not have an id but it is updated along the user navigation
 * - inactive, no session in store or session expired, waiting for a renew session
 */
export declare function startSessionStore<TrackingType extends string>(sessionStoreStrategyType: SessionStoreStrategyType, productKey: string, computeSessionState: (rawTrackingType?: string) => {
    trackingType: TrackingType;
    isTracked: boolean;
}): SessionStore;
