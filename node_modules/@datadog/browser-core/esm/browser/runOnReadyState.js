import { addEventListener } from './addEventListener';
export function runOnReadyState(configuration, expectedReadyState, callback) {
    if (document.readyState === expectedReadyState || document.readyState === 'complete') {
        callback();
    }
    else {
        var eventName = expectedReadyState === 'complete' ? "load" /* DOM_EVENT.LOAD */ : "DOMContentLoaded" /* DOM_EVENT.DOM_CONTENT_LOADED */;
        addEventListener(configuration, window, eventName, callback, { once: true });
    }
}
//# sourceMappingURL=runOnReadyState.js.map