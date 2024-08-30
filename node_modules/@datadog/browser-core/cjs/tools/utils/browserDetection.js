"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.isIE = isIE;
exports.isChromium = isChromium;
exports.isSafari = isSafari;
exports.detectBrowser = detectBrowser;
function isIE() {
    return detectBrowserCached() === 0 /* Browser.IE */;
}
function isChromium() {
    return detectBrowserCached() === 1 /* Browser.CHROMIUM */;
}
function isSafari() {
    return detectBrowserCached() === 2 /* Browser.SAFARI */;
}
var browserCache;
function detectBrowserCached() {
    return browserCache !== null && browserCache !== void 0 ? browserCache : (browserCache = detectBrowser());
}
// Exported only for tests
function detectBrowser(browserWindow) {
    var _a;
    if (browserWindow === void 0) { browserWindow = window; }
    var userAgent = browserWindow.navigator.userAgent;
    if (browserWindow.chrome || /HeadlessChrome/.test(userAgent)) {
        return 1 /* Browser.CHROMIUM */;
    }
    if (
    // navigator.vendor is deprecated, but it is the most resilient way we found to detect
    // "Apple maintained browsers" (AKA Safari). If one day it gets removed, we still have the
    // useragent test as a semi-working fallback.
    ((_a = browserWindow.navigator.vendor) === null || _a === void 0 ? void 0 : _a.indexOf('Apple')) === 0 ||
        (/safari/i.test(userAgent) && !/chrome|android/i.test(userAgent))) {
        return 2 /* Browser.SAFARI */;
    }
    if (browserWindow.document.documentMode) {
        return 0 /* Browser.IE */;
    }
    return 3 /* Browser.OTHER */;
}
//# sourceMappingURL=browserDetection.js.map