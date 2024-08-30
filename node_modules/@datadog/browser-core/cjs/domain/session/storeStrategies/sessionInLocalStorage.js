"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.selectLocalStorageStrategy = selectLocalStorageStrategy;
exports.initLocalStorageStrategy = initLocalStorageStrategy;
var stringUtils_1 = require("../../../tools/utils/stringUtils");
var sessionState_1 = require("../sessionState");
var sessionStoreStrategy_1 = require("./sessionStoreStrategy");
var LOCAL_STORAGE_TEST_KEY = '_dd_test_';
function selectLocalStorageStrategy() {
    try {
        var id = (0, stringUtils_1.generateUUID)();
        var testKey = "".concat(LOCAL_STORAGE_TEST_KEY).concat(id);
        localStorage.setItem(testKey, id);
        var retrievedId = localStorage.getItem(testKey);
        localStorage.removeItem(testKey);
        return id === retrievedId ? { type: 'LocalStorage' } : undefined;
    }
    catch (e) {
        return undefined;
    }
}
function initLocalStorageStrategy() {
    return {
        isLockEnabled: false,
        persistSession: persistInLocalStorage,
        retrieveSession: retrieveSessionFromLocalStorage,
        expireSession: expireSessionFromLocalStorage,
    };
}
function persistInLocalStorage(sessionState) {
    localStorage.setItem(sessionStoreStrategy_1.SESSION_STORE_KEY, (0, sessionState_1.toSessionString)(sessionState));
}
function retrieveSessionFromLocalStorage() {
    var sessionString = localStorage.getItem(sessionStoreStrategy_1.SESSION_STORE_KEY);
    return (0, sessionState_1.toSessionState)(sessionString);
}
function expireSessionFromLocalStorage() {
    persistInLocalStorage((0, sessionState_1.getExpiredSessionState)());
}
//# sourceMappingURL=sessionInLocalStorage.js.map