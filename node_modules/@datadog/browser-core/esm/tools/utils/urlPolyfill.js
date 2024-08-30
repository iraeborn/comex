import { jsonStringify } from '../serialisation/jsonStringify';
export function normalizeUrl(url) {
    return buildUrl(url, location.href).href;
}
export function isValidUrl(url) {
    try {
        return !!buildUrl(url);
    }
    catch (_a) {
        return false;
    }
}
export function getPathName(url) {
    var pathname = buildUrl(url).pathname;
    return pathname[0] === '/' ? pathname : "/".concat(pathname);
}
export function buildUrl(url, base) {
    var supportedURL = getSupportedUrl();
    if (supportedURL) {
        try {
            return base !== undefined ? new supportedURL(url, base) : new supportedURL(url);
        }
        catch (error) {
            throw new Error("Failed to construct URL: ".concat(String(error), " ").concat(jsonStringify({ url: url, base: base })));
        }
    }
    if (base === undefined && !/:/.test(url)) {
        throw new Error("Invalid URL: '".concat(url, "'"));
    }
    var doc = document;
    var anchorElement = doc.createElement('a');
    if (base !== undefined) {
        doc = document.implementation.createHTMLDocument('');
        var baseElement = doc.createElement('base');
        baseElement.href = base;
        doc.head.appendChild(baseElement);
        doc.body.appendChild(anchorElement);
    }
    anchorElement.href = url;
    return anchorElement;
}
var originalURL = URL;
var isURLSupported;
function getSupportedUrl() {
    if (isURLSupported === undefined) {
        try {
            var url = new originalURL('http://test/path');
            isURLSupported = url.href === 'http://test/path';
        }
        catch (_a) {
            isURLSupported = false;
        }
    }
    return isURLSupported ? originalURL : undefined;
}
//# sourceMappingURL=urlPolyfill.js.map