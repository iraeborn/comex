export function isServerError(status) {
    return status >= 500;
}
export function tryToClone(response) {
    try {
        return response.clone();
    }
    catch (e) {
        // clone can throw if the response has already been used by another instrumentation or is disturbed
        return;
    }
}
//# sourceMappingURL=responseUtils.js.map