"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.STORAGE_POLL_DELAY = void 0;
exports.selectSessionStoreStrategyType = selectSessionStoreStrategyType;
exports.startSessionStore = startSessionStore;
var timer_1 = require("../../tools/timer");
var observable_1 = require("../../tools/observable");
var timeUtils_1 = require("../../tools/utils/timeUtils");
var functionUtils_1 = require("../../tools/utils/functionUtils");
var stringUtils_1 = require("../../tools/utils/stringUtils");
var polyfills_1 = require("../../tools/utils/polyfills");
var sessionInCookie_1 = require("./storeStrategies/sessionInCookie");
var sessionState_1 = require("./sessionState");
var sessionInLocalStorage_1 = require("./storeStrategies/sessionInLocalStorage");
var sessionStoreOperations_1 = require("./sessionStoreOperations");
/**
 * Every second, the storage will be polled to check for any change that can occur
 * to the session state in another browser tab, or another window.
 * This value has been determined from our previous cookie-only implementation.
 */
exports.STORAGE_POLL_DELAY = timeUtils_1.ONE_SECOND;
/**
 * Checks if cookies are available as the preferred storage
 * Else, checks if LocalStorage is allowed and available
 */
function selectSessionStoreStrategyType(initConfiguration) {
    var sessionStoreStrategyType = (0, sessionInCookie_1.selectCookieStrategy)(initConfiguration);
    if (!sessionStoreStrategyType && initConfiguration.allowFallbackToLocalStorage) {
        sessionStoreStrategyType = (0, sessionInLocalStorage_1.selectLocalStorageStrategy)();
    }
    return sessionStoreStrategyType;
}
/**
 * Different session concepts:
 * - tracked, the session has an id and is updated along the user navigation
 * - not tracked, the session does not have an id but it is updated along the user navigation
 * - inactive, no session in store or session expired, waiting for a renew session
 */
function startSessionStore(sessionStoreStrategyType, productKey, computeSessionState) {
    var renewObservable = new observable_1.Observable();
    var expireObservable = new observable_1.Observable();
    var sessionStateUpdateObservable = new observable_1.Observable();
    var sessionStoreStrategy = sessionStoreStrategyType.type === 'Cookie'
        ? (0, sessionInCookie_1.initCookieStrategy)(sessionStoreStrategyType.cookieOptions)
        : (0, sessionInLocalStorage_1.initLocalStorageStrategy)();
    var expireSession = sessionStoreStrategy.expireSession;
    var watchSessionTimeoutId = (0, timer_1.setInterval)(watchSession, exports.STORAGE_POLL_DELAY);
    var sessionCache;
    startSession();
    var _a = (0, functionUtils_1.throttle)(function () {
        (0, sessionStoreOperations_1.processSessionStoreOperations)({
            process: function (sessionState) {
                if ((0, sessionState_1.isSessionInNotStartedState)(sessionState)) {
                    return;
                }
                var synchronizedSession = synchronizeSession(sessionState);
                expandOrRenewSessionState(synchronizedSession);
                return synchronizedSession;
            },
            after: function (sessionState) {
                if ((0, sessionState_1.isSessionStarted)(sessionState) && !hasSessionInCache()) {
                    renewSessionInCache(sessionState);
                }
                sessionCache = sessionState;
            },
        }, sessionStoreStrategy);
    }, exports.STORAGE_POLL_DELAY), throttledExpandOrRenewSession = _a.throttled, cancelExpandOrRenewSession = _a.cancel;
    function expandSession() {
        (0, sessionStoreOperations_1.processSessionStoreOperations)({
            process: function (sessionState) { return (hasSessionInCache() ? synchronizeSession(sessionState) : undefined); },
        }, sessionStoreStrategy);
    }
    /**
     * allows two behaviors:
     * - if the session is active, synchronize the session cache without updating the session store
     * - if the session is not active, clear the session store and expire the session cache
     */
    function watchSession() {
        (0, sessionStoreOperations_1.processSessionStoreOperations)({
            process: function (sessionState) { return ((0, sessionState_1.isSessionInExpiredState)(sessionState) ? (0, sessionState_1.getExpiredSessionState)() : undefined); },
            after: synchronizeSession,
        }, sessionStoreStrategy);
    }
    function synchronizeSession(sessionState) {
        if ((0, sessionState_1.isSessionInExpiredState)(sessionState)) {
            sessionState = (0, sessionState_1.getExpiredSessionState)();
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
        (0, sessionStoreOperations_1.processSessionStoreOperations)({
            process: function (sessionState) {
                if ((0, sessionState_1.isSessionInNotStartedState)(sessionState)) {
                    return (0, sessionState_1.getExpiredSessionState)();
                }
            },
            after: function (sessionState) {
                sessionCache = sessionState;
            },
        }, sessionStoreStrategy);
    }
    function expandOrRenewSessionState(sessionState) {
        if ((0, sessionState_1.isSessionInNotStartedState)(sessionState)) {
            return false;
        }
        var _a = computeSessionState(sessionState[productKey]), trackingType = _a.trackingType, isTracked = _a.isTracked;
        sessionState[productKey] = trackingType;
        delete sessionState.isExpired;
        if (isTracked && !sessionState.id) {
            sessionState.id = (0, stringUtils_1.generateUUID)();
            sessionState.created = String((0, timeUtils_1.dateNow)());
        }
    }
    function hasSessionInCache() {
        return sessionCache[productKey] !== undefined;
    }
    function isSessionInCacheOutdated(sessionState) {
        return sessionCache.id !== sessionState.id || sessionCache[productKey] !== sessionState[productKey];
    }
    function expireSessionInCache() {
        sessionCache = (0, sessionState_1.getExpiredSessionState)();
        expireObservable.notify();
    }
    function renewSessionInCache(sessionState) {
        sessionCache = sessionState;
        renewObservable.notify();
    }
    function updateSessionState(partialSessionState) {
        (0, sessionStoreOperations_1.processSessionStoreOperations)({
            process: function (sessionState) { return (0, polyfills_1.assign)({}, sessionState, partialSessionState); },
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
            synchronizeSession((0, sessionState_1.getExpiredSessionState)());
        },
        stop: function () {
            (0, timer_1.clearInterval)(watchSessionTimeoutId);
        },
        updateSessionState: updateSessionState,
    };
}
//# sourceMappingURL=sessionStore.js.map