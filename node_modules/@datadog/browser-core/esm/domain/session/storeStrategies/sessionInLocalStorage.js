import { generateUUID } from '../../../tools/utils/stringUtils';
import { toSessionString, toSessionState, getExpiredSessionState } from '../sessionState';
import { SESSION_STORE_KEY } from './sessionStoreStrategy';
var LOCAL_STORAGE_TEST_KEY = '_dd_test_';
export function selectLocalStorageStrategy() {
    try {
        var id = generateUUID();
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
export function initLocalStorageStrategy() {
    return {
        isLockEnabled: false,
        persistSession: persistInLocalStorage,
        retrieveSession: retrieveSessionFromLocalStorage,
        expireSession: expireSessionFromLocalStorage,
    };
}
function persistInLocalStorage(sessionState) {
    localStorage.setItem(SESSION_STORE_KEY, toSessionString(sessionState));
}
function retrieveSessionFromLocalStorage() {
    var sessionString = localStorage.getItem(SESSION_STORE_KEY);
    return toSessionState(sessionString);
}
function expireSessionFromLocalStorage() {
    persistInLocalStorage(getExpiredSessionState());
}
//# sourceMappingURL=sessionInLocalStorage.js.map