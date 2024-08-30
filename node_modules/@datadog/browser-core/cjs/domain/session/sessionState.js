"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.EXPIRED = void 0;
exports.getExpiredSessionState = getExpiredSessionState;
exports.isSessionInNotStartedState = isSessionInNotStartedState;
exports.isSessionStarted = isSessionStarted;
exports.isSessionInExpiredState = isSessionInExpiredState;
exports.expandSessionState = expandSessionState;
exports.toSessionString = toSessionString;
exports.toSessionState = toSessionState;
var objectUtils_1 = require("../../tools/utils/objectUtils");
var polyfills_1 = require("../../tools/utils/polyfills");
var timeUtils_1 = require("../../tools/utils/timeUtils");
var sessionConstants_1 = require("./sessionConstants");
var SESSION_ENTRY_REGEXP = /^([a-zA-Z]+)=([a-z0-9-]+)$/;
var SESSION_ENTRY_SEPARATOR = '&';
exports.EXPIRED = '1';
function getExpiredSessionState() {
    return {
        isExpired: exports.EXPIRED,
    };
}
function isSessionInNotStartedState(session) {
    return (0, objectUtils_1.isEmptyObject)(session);
}
function isSessionStarted(session) {
    return !isSessionInNotStartedState(session);
}
function isSessionInExpiredState(session) {
    return session.isExpired !== undefined || !isActiveSession(session);
}
// An active session is a session in either `Tracked` or `NotTracked` state
function isActiveSession(sessionState) {
    // created and expire can be undefined for versions which was not storing them
    // these checks could be removed when older versions will not be available/live anymore
    return ((sessionState.created === undefined || (0, timeUtils_1.dateNow)() - Number(sessionState.created) < sessionConstants_1.SESSION_TIME_OUT_DELAY) &&
        (sessionState.expire === undefined || (0, timeUtils_1.dateNow)() < Number(sessionState.expire)));
}
function expandSessionState(session) {
    session.expire = String((0, timeUtils_1.dateNow)() + sessionConstants_1.SESSION_EXPIRATION_DELAY);
}
function toSessionString(session) {
    return (0, polyfills_1.objectEntries)(session)
        .map(function (_a) {
        var key = _a[0], value = _a[1];
        return "".concat(key, "=").concat(value);
    })
        .join(SESSION_ENTRY_SEPARATOR);
}
function toSessionState(sessionString) {
    var session = {};
    if (isValidSessionString(sessionString)) {
        sessionString.split(SESSION_ENTRY_SEPARATOR).forEach(function (entry) {
            var matches = SESSION_ENTRY_REGEXP.exec(entry);
            if (matches !== null) {
                var key = matches[1], value = matches[2];
                session[key] = value;
            }
        });
    }
    return session;
}
function isValidSessionString(sessionString) {
    return (!!sessionString &&
        (sessionString.indexOf(SESSION_ENTRY_SEPARATOR) !== -1 || SESSION_ENTRY_REGEXP.test(sessionString)));
}
//# sourceMappingURL=sessionState.js.map