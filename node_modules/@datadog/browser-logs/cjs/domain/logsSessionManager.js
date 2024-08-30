"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.LOGS_SESSION_KEY = void 0;
exports.startLogsSessionManager = startLogsSessionManager;
exports.startLogsSessionManagerStub = startLogsSessionManagerStub;
var browser_core_1 = require("@datadog/browser-core");
exports.LOGS_SESSION_KEY = 'logs';
function startLogsSessionManager(configuration, trackingConsentState) {
    var sessionManager = (0, browser_core_1.startSessionManager)(configuration, exports.LOGS_SESSION_KEY, function (rawTrackingType) { return computeSessionState(configuration, rawTrackingType); }, trackingConsentState);
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
function startLogsSessionManagerStub(configuration) {
    var isTracked = computeTrackingType(configuration) === "1" /* LoggerTrackingType.TRACKED */;
    var session = isTracked ? {} : undefined;
    return {
        findTrackedSession: function () { return session; },
        expireObservable: new browser_core_1.Observable(),
    };
}
function computeTrackingType(configuration) {
    if (!(0, browser_core_1.performDraw)(configuration.sessionSampleRate)) {
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