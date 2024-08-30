import { Observable } from '../tools/observable';
export declare const TrackingConsent: {
    readonly GRANTED: "granted";
    readonly NOT_GRANTED: "not-granted";
};
export type TrackingConsent = (typeof TrackingConsent)[keyof typeof TrackingConsent];
export interface TrackingConsentState {
    tryToInit: (trackingConsent: TrackingConsent) => void;
    update: (trackingConsent: TrackingConsent) => void;
    isGranted: () => boolean;
    observable: Observable<void>;
}
export declare function createTrackingConsentState(currentConsent?: TrackingConsent): TrackingConsentState;
