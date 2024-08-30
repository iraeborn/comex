"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.LOGS_SESSION_KEY = exports.RUM_SESSION_KEY = exports.OLD_LOGS_COOKIE_NAME = exports.OLD_RUM_COOKIE_NAME = exports.OLD_SESSION_COOKIE_NAME = void 0;
exports.tryOldCookiesMigration = tryOldCookiesMigration;
var cookie_1 = require("../../browser/cookie");
var sessionStoreStrategy_1 = require("./storeStrategies/sessionStoreStrategy");
var sessionState_1 = require("./sessionState");
exports.OLD_SESSION_COOKIE_NAME = '_dd';
exports.OLD_RUM_COOKIE_NAME = '_dd_r';
exports.OLD_LOGS_COOKIE_NAME = '_dd_l';
// duplicate values to avoid dependency issues
exports.RUM_SESSION_KEY = 'rum';
exports.LOGS_SESSION_KEY = 'logs';
/**
 * This migration should remain in the codebase as long as older versions are available/live
 * to allow older sdk versions to be upgraded to newer versions without compatibility issues.
 */
function tryOldCookiesMigration(cookieStoreStrategy) {
    var sessionString = (0, cookie_1.getInitCookie)(sessionStoreStrategy_1.SESSION_STORE_KEY);
    if (!sessionString) {
        var oldSessionId = (0, cookie_1.getInitCookie)(exports.OLD_SESSION_COOKIE_NAME);
        var oldRumType = (0, cookie_1.getInitCookie)(exports.OLD_RUM_COOKIE_NAME);
        var oldLogsType = (0, cookie_1.getInitCookie)(exports.OLD_LOGS_COOKIE_NAME);
        var session = {};
        if (oldSessionId) {
            session.id = oldSessionId;
        }
        if (oldLogsType && /^[01]$/.test(oldLogsType)) {
            session[exports.LOGS_SESSION_KEY] = oldLogsType;
        }
        if (oldRumType && /^[012]$/.test(oldRumType)) {
            session[exports.RUM_SESSION_KEY] = oldRumType;
        }
        if ((0, sessionState_1.isSessionStarted)(session)) {
            (0, sessionState_1.expandSessionState)(session);
            cookieStoreStrategy.persistSession(session);
        }
    }
}
//# sourceMappingURL=oldCookiesMigration.js.map