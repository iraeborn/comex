"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.selectCookieStrategy = selectCookieStrategy;
exports.initCookieStrategy = initCookieStrategy;
exports.buildCookieOptions = buildCookieOptions;
var browserDetection_1 = require("../../../tools/utils/browserDetection");
var cookie_1 = require("../../../browser/cookie");
var oldCookiesMigration_1 = require("../oldCookiesMigration");
var sessionConstants_1 = require("../sessionConstants");
var sessionState_1 = require("../sessionState");
var sessionStoreStrategy_1 = require("./sessionStoreStrategy");
function selectCookieStrategy(initConfiguration) {
    var cookieOptions = buildCookieOptions(initConfiguration);
    return (0, cookie_1.areCookiesAuthorized)(cookieOptions) ? { type: 'Cookie', cookieOptions: cookieOptions } : undefined;
}
function initCookieStrategy(cookieOptions) {
    var cookieStore = {
        /**
         * Lock strategy allows mitigating issues due to concurrent access to cookie.
         * This issue concerns only chromium browsers and enabling this on firefox increases cookie write failures.
         */
        isLockEnabled: (0, browserDetection_1.isChromium)(),
        persistSession: persistSessionCookie(cookieOptions),
        retrieveSession: retrieveSessionCookie,
        expireSession: function () { return expireSessionCookie(cookieOptions); },
    };
    (0, oldCookiesMigration_1.tryOldCookiesMigration)(cookieStore);
    return cookieStore;
}
function persistSessionCookie(options) {
    return function (session) {
        (0, cookie_1.setCookie)(sessionStoreStrategy_1.SESSION_STORE_KEY, (0, sessionState_1.toSessionString)(session), sessionConstants_1.SESSION_EXPIRATION_DELAY, options);
    };
}
function expireSessionCookie(options) {
    (0, cookie_1.setCookie)(sessionStoreStrategy_1.SESSION_STORE_KEY, (0, sessionState_1.toSessionString)((0, sessionState_1.getExpiredSessionState)()), sessionConstants_1.SESSION_TIME_OUT_DELAY, options);
}
function retrieveSessionCookie() {
    var sessionString = (0, cookie_1.getCookie)(sessionStoreStrategy_1.SESSION_STORE_KEY);
    return (0, sessionState_1.toSessionState)(sessionString);
}
function buildCookieOptions(initConfiguration) {
    var cookieOptions = {};
    cookieOptions.secure =
        !!initConfiguration.useSecureSessionCookie ||
            !!initConfiguration.usePartitionedCrossSiteSessionCookie ||
            !!initConfiguration.useCrossSiteSessionCookie;
    cookieOptions.crossSite =
        !!initConfiguration.usePartitionedCrossSiteSessionCookie || !!initConfiguration.useCrossSiteSessionCookie;
    cookieOptions.partitioned = !!initConfiguration.usePartitionedCrossSiteSessionCookie;
    if (initConfiguration.trackSessionAcrossSubdomains) {
        cookieOptions.domain = (0, cookie_1.getCurrentSite)();
    }
    return cookieOptions;
}
//# sourceMappingURL=sessionInCookie.js.map