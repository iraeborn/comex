import { clearInterval, setInterval } from '../../tools/timer';
import { Observable } from '../../tools/observable';
import { ONE_SECOND, dateNow } from '../../tools/utils/timeUtils';
import { throttle } from '../../tools/utils/functionUtils';
import { generateUUID } from '../../tools/utils/stringUtils';
import { assign } from '../../tools/utils/polyfills';
import { selectCookieStrategy, initCookieStrategy } from './storeStrategies/sessionInCookie';
import { getExpiredSessionState, isSessionInExpiredState, isSessionInNotStartedState, isSessionStarted, } from './sessionState';
import { initLocalStorageStrategy, selectLocalStorageStrategy } from './storeStrategies/sessionInLocalStorage';
import { processSessionStoreOperations } from './sessionStoreOperations';
/**
 * Every second, the storage will be polled to check for any change that can occur
 * to the session state in another browser tab, or another window.
 * This value has been determined from our previous cookie-only implementation.
 */
export var STORAGE_POLL_DELAY = ONE_SECOND;
/**
 * Checks if cookies are available as the preferred storage
 * Else, checks if LocalStorage is allowed and available
 */
export function selectSessionStoreStrategyType(initConfiguration) {
    var sessionStoreStrategyType = selectCookieStrategy(initConfiguration);
    if (!sessionStoreStrategyType && initConfiguration.allowFallbackToLocalStorage) {
        sessionStoreStrategyType = selectLocalStorageStrategy();
    }
    return sessionStoreStrategyType;
}
/**
 * Different session concepts:
 * - tracked, the session has an id and is updated along the user navigation
 * - not tracked, the session does not have an id but it is updated along the user navigation
 * - inactive, no session in store or session expired, waiting for a renew session
 */
export function startSessionStore(sessionStoreStrategyType, productKey, computeSessionState) {
    var renewObservable = new Observable();
    var expireObservable = new Observable();
    var sessionStateUpdateObservable = new Observable();
    var sessionStoreStrategy = sessionStoreStrategyType.type === 'Cookie'
        ? initCookieStrategy(sessionStoreStrategyType.cookieOptions)
        : initLocalStorageStrategy();
    var expireSession = sessionStoreStrategy.expireSession;
    var watchSessionTimeoutId = setInterval(watchSession, STORAGE_POLL_DELAY);
    var sessionCache;
    startSession();
    var _a = throttle(function () {
        processSessionStoreOperations({
            process: function (sessionState) {
                if (isSessionInNotStartedState(sessionState)) {
                    return;
                }
                var synchronizedSession = synchronizeSession(sessionState);
                expandOrRenewSessionState(synchronizedSession);
                return synchronizedSession;
            },
            after: function (sessionState) {
                if (isSessionStarted(sessionState) && !hasSessionInCache()) {
                    renewSessionInCache(sessionState);
                }
                sessionCache = sessionState;
            },
        }, sessionStoreStrategy);
    }, STORAGE_POLL_DELAY), throttledExpandOrRenewSession = _a.throttled, cancelExpandOrRenewSession = _a.cancel;
    function expandSession() {
        processSessionStoreOperations({
            process: function (sessionState) { return (hasSessionInCache() ? synchronizeSession(sessionState) : undefined); },
        }, sessionStoreStrategy);
    }
    /**
     * allows two behaviors:
     * - if the session is active, synchronize the session cache without updating the session store
     * - if the session is not active, clear the session store and expire the session cache
     */
    function watchSession() {
        processSessionStoreOperations({
            process: function (sessionState) { return (isSessionInExpiredState(sessionState) ? getExpiredSessionState() : undefined); },
            after: synchronizeSession,
        }, sessionStoreStrategy);
    }
    function synchronizeSession(sessionState) {
        if (isSessionInExpiredState(sessionState)) {
            sessionState = getExpiredSessionState();
        }
        if (hasSessionInCache()) {
            if (isSessionInCacheOutdated(sessionState)) {
                expireSessionInCache();
            }
            else {
                sessionStateUpdateObservable.notify({ previousState: sessionCache, newState: sessionState });
                sessionCache = sessionState;
            }
        }
        return sessionState;
    }
    function startSession() {
        processSessionStoreOperations({
            process: function (sessionState) {
                if (isSessionInNotStartedState(sessionState)) {
                    return getExpiredSessionState();
                }
            },
            after: function (sessionState) {
                sessionCache = sessionState;
            },
        }, sessionStoreStrategy);
    }
    function expandOrRenewSessionState(sessionState) {
        if (isSessionInNotStartedState(sessionState)) {
            return false;
        }
        var _a = computeSessionState(sessionState[productKey]), trackingType = _a.trackingType, isTracked = _a.isTracked;
        sessionState[productKey] = trackingType;
        delete sessionState.isExpired;
        if (isTracked && !sessionState.id) {
            sessionState.id = generateUUID();
            sessionState.created = String(dateNow());
        }
    }
    function hasSessionInCache() {
        return sessionCache[productKey] !== undefined;
    }
    function isSessionInCacheOutdated(sessionState) {
        return sessionCache.id !== sessionState.id || sessionCache[productKey] !== sessionState[productKey];
    }
    function expireSessionInCache() {
        sessionCache = getExpiredSessionState();
        expireObservable.notify();
    }
    function renewSessionInCache(sessionState) {
        sessionCache = sessionState;
        renewObservable.notify();
    }
    function updateSessionState(partialSessionState) {
        processSessionStoreOperations({
            process: function (sessionState) { return assign({}, sessionState, partialSessionState); },
            after: synchronizeSession,
        }, sessionStoreStrategy);
    }
    return {
        expandOrRenewSession: throttledExpandOrRenewSession,
        expandSession: expandSession,
        getSession: function () { return sessionCache; },
        renewObservable: renewObservable,
        expireObservable: expireObservable,
        sessionStateUpdateObservable: sessionStateUpdateObservable,
        restartSession: startSession,
        expire: function () {
            cancelExpandOrRenewSession();
            expireSession();
            synchronizeSession(getExpiredSessionState());
        },
        stop: function () {
            clearInterval(watchSessionTimeoutId);
        },
        updateSessionState: updateSessionState,
    };
}
//# sourceMappingURL=sessionStore.js.map