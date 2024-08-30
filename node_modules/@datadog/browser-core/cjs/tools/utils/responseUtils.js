"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.isServerError = isServerError;
exports.tryToClone = tryToClone;
function isServerError(status) {
    return status >= 500;
}
function tryToClone(response) {
    try {
        return response.clone();
    }
    catch (e) {
        // clone can throw if the response has already been used by another instrumentation or is disturbed
        return;
    }
}
//# sourceMappingURL=responseUtils.js.map