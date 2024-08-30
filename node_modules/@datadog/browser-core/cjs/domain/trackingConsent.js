"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.TrackingConsent = void 0;
exports.createTrackingConsentState = createTrackingConsentState;
var observable_1 = require("../tools/observable");
exports.TrackingConsent = {
    GRANTED: 'granted',
    NOT_GRANTED: 'not-granted',
};
function createTrackingConsentState(currentConsent) {
    var observable = new observable_1.Observable();
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
            return currentConsent === exports.TrackingConsent.GRANTED;
        },
        observable: observable,
    };
}
//# sourceMappingURL=trackingConsent.js.map