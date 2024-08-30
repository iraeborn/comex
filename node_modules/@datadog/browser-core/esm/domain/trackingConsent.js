import { Observable } from '../tools/observable';
export var TrackingConsent = {
    GRANTED: 'granted',
    NOT_GRANTED: 'not-granted',
};
export function createTrackingConsentState(currentConsent) {
    var observable = new Observable();
    return {
        tryToInit: function (trackingConsent) {
            if (!currentConsent) {
                currentConsent = trackingConsent;
            }
        },
        update: function (trackingConsent) {
            currentConsent = trackingConsent;
            observable.notify();
        },
        isGranted: function () {
            return currentConsent === TrackingConsent.GRANTED;
        },
        observable: observable,
    };
}
//# sourceMappingURL=trackingConsent.js.map