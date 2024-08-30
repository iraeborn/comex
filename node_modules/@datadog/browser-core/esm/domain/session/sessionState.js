import { isEmptyObject } from '../../tools/utils/objectUtils';
import { objectEntries } from '../../tools/utils/polyfills';
import { dateNow } from '../../tools/utils/timeUtils';
import { SESSION_EXPIRATION_DELAY, SESSION_TIME_OUT_DELAY } from './sessionConstants';
var SESSION_ENTRY_REGEXP = /^([a-zA-Z]+)=([a-z0-9-]+)$/;
var SESSION_ENTRY_SEPARATOR = '&';
export var EXPIRED = '1';
export function getExpiredSessionState() {
    return {
        isExpired: EXPIRED,
    };
}
export function isSessionInNotStartedState(session) {
    return isEmptyObject(session);
}
export function isSessionStarted(session) {
    return !isSessionInNotStartedState(session);
}
export function isSessionInExpiredState(session) {
    return session.isExpired !== undefined || !isActiveSession(session);
}
// An active session is a session in either `Tracked` or `NotTracked` state
function isActiveSession(sessionState) {
    // created and expire can be undefined for versions which was not storing them
    // these checks could be removed when older versions will not be available/live anymore
    return ((sessionState.created === undefined || dateNow() - Number(sessionState.created) < SESSION_TIME_OUT_DELAY) &&
        (sessionState.expire === undefined || dateNow() < Number(sessionState.expire)));
}
export function expandSessionState(session) {
    session.expire = String(dateNow() + SESSION_EXPIRATION_DELAY);
}
export function toSessionString(session) {
    return objectEntries(session)
        .map(function (_a) {
        var key = _a[0], value = _a[1];
        return "".concat(key, "=").concat(value);
    })
        .join(SESSION_ENTRY_SEPARATOR);
}
export function toSessionState(sessionString) {
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