"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.LOCK_MAX_TRIES = exports.LOCK_RETRY_DELAY = void 0;
exports.processSessionStoreOperations = processSessionStoreOperations;
var timer_1 = require("../../tools/timer");
var stringUtils_1 = require("../../tools/utils/stringUtils");
var polyfills_1 = require("../../tools/utils/polyfills");
var sessionState_1 = require("./sessionState");
exports.LOCK_RETRY_DELAY = 10;
exports.LOCK_MAX_TRIES = 100;
var bufferedOperations = [];
var ongoingOperations;
function processSessionStoreOperations(operations, sessionStoreStrategy, numberOfRetries) {
    var _a;
    if (numberOfRetries === void 0) { numberOfRetries = 0; }
    var isLockEnabled = sessionStoreStrategy.isLockEnabled, persistSession = sessionStoreStrategy.persistSession, expireSession = sessionStoreStrategy.expireSession;
    var persistWithLock = function (session) { return persistSession((0, polyfills_1.assign)({}, session, { lock: currentLock })); };
    var retrieveStore = function () {
        var session = sessionStoreStrategy.retrieveSession();
        var lock = session.lock;
        if (session.lock) {
            delete session.lock;
        }
        return {
            session: session,
            lock: lock,
        };
    };
    if (!ongoingOperations) {
        ongoingOperations = operations;
    }
    if (operations !== ongoingOperations) {
        bufferedOperations.push(operations);
        return;
    }
    if (isLockEnabled && numberOfRetries >= exports.LOCK_MAX_TRIES) {
        next(sessionStoreStrategy);
        return;
    }
    var currentLock;
    var currentStore = retrieveStore();
    if (isLockEnabled) {
        // if someone has lock, retry later
        if (currentStore.lock) {
            retryLater(operations, sessionStoreStrategy, numberOfRetries);
            return;
        }
        // acquire lock
        currentLock = (0, stringUtils_1.generateUUID)();
        persistWithLock(currentStore.session);
        // if lock is not acquired, retry later
        currentStore = retrieveStore();
        if (currentStore.lock !== currentLock) {
            retryLater(operations, sessionStoreStrategy, numberOfRetries);
            return;
        }
    }
    var processedSession = operations.process(currentStore.session);
    if (isLockEnabled) {
        // if lock corrupted after process, retry later
        currentStore = retrieveStore();
        if (currentStore.lock !== currentLock) {
            retryLater(operations, sessionStoreStrategy, numberOfRetries);
            return;
        }
    }
    if (processedSession) {
        if ((0, sessionState_1.isSessionInExpiredState)(processedSession)) {
            expireSession();
        }
        else {
            (0, sessionState_1.expandSessionState)(processedSession);
            isLockEnabled ? persistWithLock(processedSession) : persistSession(processedSession);
        }
    }
    if (isLockEnabled) {
        // correctly handle lock around expiration would require to handle this case properly at several levels
        // since we don't have evidence of lock issues around expiration, let's just not do the corruption check for it
        if (!(processedSession && (0, sessionState_1.isSessionInExpiredState)(processedSession))) {
            // if lock corrupted after persist, retry later
            currentStore = retrieveStore();
            if (currentStore.lock !== currentLock) {
                retryLater(operations, sessionStoreStrategy, numberOfRetries);
                return;
            }
            persistSession(currentStore.session);
            processedSession = currentStore.session;
        }
    }
    // call after even if session is not persisted in order to perform operations on
    // up-to-date session state value => the value could have been modified by another tab
    (_a = operations.after) === null || _a === void 0 ? void 0 : _a.call(operations, processedSession || currentStore.session);
    next(sessionStoreStrategy);
}
function retryLater(operations, sessionStore, currentNumberOfRetries) {
    (0, timer_1.setTimeout)(function () {
        processSessionStoreOperations(operations, sessionStore, currentNumberOfRetries + 1);
    }, exports.LOCK_RETRY_DELAY);
}
function next(sessionStore) {
    ongoingOperations = undefined;
    var nextOperations = bufferedOperations.shift();
    if (nextOperations) {
        processSessionStoreOperations(nextOperations, sessionStore);
    }
}
//# sourceMappingURL=sessionStoreOperations.js.map