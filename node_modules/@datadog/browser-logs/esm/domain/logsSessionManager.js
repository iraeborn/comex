import { Observable, performDraw, startSessionManager } from '@datadog/browser-core';
export var LOGS_SESSION_KEY = 'logs';
export function startLogsSessionManager(configuration, trackingConsentState) {
    var sessionManager = startSessionManager(configuration, LOGS_SESSION_KEY, function (rawTrackingType) { return computeSessionState(configuration, rawTrackingType); }, trackingConsentState);
    return {
        findTrackedSession: function (startTime, options) {
            if (options === void 0) { options = { returnInactive: false }; }
            var session = sessionManager.findSession(startTime, options);
            return session && session.trackingType === "1" /* LoggerTrackingType.TRACKED */
                ? {
                    id: session.id,
                }
                : undefined;
        },
        expireObservable: sessionManager.expireObservable,
    };
}
export function startLogsSessionManagerStub(configuration) {
    var isTracked = computeTrackingType(configuration) === "1" /* LoggerTrackingType.TRACKED */;
    var session = isTracked ? {} : undefined;
    return {
        findTrackedSession: function () { return session; },
        expireObservable: new Observable(),
    };
}
function computeTrackingType(configuration) {
    if (!performDraw(configuration.sessionSampleRate)) {
        return "0" /* LoggerTrackingType.NOT_TRACKED */;
    }
    return "1" /* LoggerTrackingType.TRACKED */;
}
function computeSessionState(configuration, rawSessionType) {
    var trackingType = hasValidLoggerSession(rawSessionType) ? rawSessionType : computeTrackingType(configuration);
    return {
        trackingType: trackingType,
        isTracked: trackingType === "1" /* LoggerTrackingType.TRACKED */,
    };
}
function hasValidLoggerSession(trackingType) {
    return trackingType === "0" /* LoggerTrackingType.NOT_TRACKED */ || trackingType === "1" /* LoggerTrackingType.TRACKED */;
}
//# sourceMappingURL=logsSessionManager.js.map