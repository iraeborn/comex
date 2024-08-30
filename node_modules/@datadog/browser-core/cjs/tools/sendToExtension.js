"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.sendToExtension = sendToExtension;
function sendToExtension(type, payload) {
    var callback = window.__ddBrowserSdkExtensionCallback;
    if (callback) {
        callback({ type: type, payload: payload });
    }
}
//# sourceMappingURL=sendToExtension.js.map