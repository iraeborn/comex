import { isChromium } from '../../../tools/utils/browserDetection';
import { getCurrentSite, areCookiesAuthorized, getCookie, setCookie } from '../../../browser/cookie';
import { tryOldCookiesMigration } from '../oldCookiesMigration';
import { SESSION_EXPIRATION_DELAY, SESSION_TIME_OUT_DELAY } from '../sessionConstants';
import { toSessionString, toSessionState, getExpiredSessionState } from '../sessionState';
import { SESSION_STORE_KEY } from './sessionStoreStrategy';
export function selectCookieStrategy(initConfiguration) {
    var cookieOptions = buildCookieOptions(initConfiguration);
    return areCookiesAuthorized(cookieOptions) ? { type: 'Cookie', cookieOptions: cookieOptions } : undefined;
}
export function initCookieStrategy(cookieOptions) {
    var cookieStore = {
        /**
         * Lock strategy allows mitigating issues due to concurrent access to cookie.
         * This issue concerns only chromium browsers and enabling this on firefox increases cookie write failures.
         */
        isLockEnabled: isChromium(),
        persistSession: persistSessionCookie(cookieOptions),
        retrieveSession: retrieveSessionCookie,
        expireSession: function () { return expireSessionCookie(cookieOptions); },
    };
    tryOldCookiesMigration(cookieStore);
    return cookieStore;
}
function persistSessionCookie(options) {
    return function (session) {
        setCookie(SESSION_STORE_KEY, toSessionString(session), SESSION_EXPIRATION_DELAY, options);
    };
}
function expireSessionCookie(options) {
    setCookie(SESSION_STORE_KEY, toSessionString(getExpiredSessionState()), SESSION_TIME_OUT_DELAY, options);
}
function retrieveSessionCookie() {
    var sessionString = getCookie(SESSION_STORE_KEY);
    return toSessionState(sessionString);
}
export function buildCookieOptions(initConfiguration) {
    var cookieOptions = {};
    cookieOptions.secure =
        !!initConfiguration.useSecureSessionCookie ||
            !!initConfiguration.usePartitionedCrossSiteSessionCookie ||
            !!initConfiguration.useCrossSiteSessionCookie;
    cookieOptions.crossSite =
        !!initConfiguration.usePartitionedCrossSiteSessionCookie || !!initConfiguration.useCrossSiteSessionCookie;
    cookieOptions.partitioned = !!initConfiguration.usePartitionedCrossSiteSessionCookie;
    if (initConfiguration.trackSessionAcrossSubdomains) {
        cookieOptions.domain = getCurrentSite();
    }
    return cookieOptions;
}
//# sourceMappingURL=sessionInCookie.js.map