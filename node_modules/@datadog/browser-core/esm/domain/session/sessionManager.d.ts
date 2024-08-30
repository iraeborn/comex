import { Observable } from '../../tools/observable';
import type { Context } from '../../tools/serialisation/context';
import type { RelativeTime } from '../../tools/utils/timeUtils';
import type { Configuration } from '../configuration';
import type { TrackingConsentState } from '../trackingConsent';
import type { SessionState } from './sessionState';
export interface SessionManager<TrackingType extends string> {
    findSession: (startTime?: RelativeTime, options?: {
        returnInactive: boolean;
    }) => SessionContext<TrackingType> | undefined;
    renewObservable: Observable<void>;
    expireObservable: Observable<void>;
    sessionStateUpdateObservable: Observable<{
        previousState: SessionState;
        newState: SessionState;
    }>;
    expire: () => void;
    updateSessionState: (state: Partial<SessionState>) => void;
}
export interface SessionContext<TrackingType extends string> extends Context {
    id: string;
    trackingType: TrackingType;
    isReplayForced: boolean;
}
export declare const VISIBILITY_CHECK_DELAY: number;
export declare function startSessionManager<TrackingType extends string>(configuration: Configuration, productKey: string, computeSessionState: (rawTrackingType?: string) => {
    trackingType: TrackingType;
    isTracked: boolean;
}, trackingConsentState: TrackingConsentState): SessionManager<TrackingType>;
export declare function stopSessionManager(): void;
