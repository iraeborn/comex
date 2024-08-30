"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getEventBridge = getEventBridge;
exports.bridgeSupports = bridgeSupports;
exports.canUseEventBridge = canUseEventBridge;
var polyfills_1 = require("../tools/utils/polyfills");
var getGlobalObject_1 = require("../tools/getGlobalObject");
function getEventBridge() {
    var eventBridgeGlobal = getEventBridgeGlobal();
    if (!eventBridgeGlobal) {
        return;
    }
    return {
        getCapabilities: function () {
            var _a;
            return JSON.parse(((_a = eventBridgeGlobal.getCapabilities) === null || _a === void 0 ? void 0 : _a.call(eventBridgeGlobal)) || '[]');
        },
        getPrivacyLevel: function () {
            var _a;
            return (_a = eventBridgeGlobal.getPrivacyLevel) === null || _a === void 0 ? void 0 : _a.call(eventBridgeGlobal);
        },
        getAllowedWebViewHosts: function () {
            return JSON.parse(eventBridgeGlobal.getAllowedWebViewHosts());
        },
        send: function (eventType, event, viewId) {
            var view = viewId ? { id: viewId } : undefined;
            eventBridgeGlobal.send(JSON.stringify({ eventType: eventType, event: event, view: view }));
        },
    };
}
function bridgeSupports(capability) {
    var bridge = getEventBridge();
    return !!bridge && (0, polyfills_1.includes)(bridge.getCapabilities(), capability);
}
function canUseEventBridge(currentHost) {
    var _a;
    if (currentHost === void 0) { currentHost = (_a = (0, getGlobalObject_1.getGlobalObject)().location) === null || _a === void 0 ? void 0 : _a.hostname; }
    var bridge = getEventBridge();
    return (!!bridge &&
        bridge
            .getAllowedWebViewHosts()
            .some(function (allowedHost) { return currentHost === allowedHost || (0, polyfills_1.endsWith)(currentHost, ".".concat(allowedHost)); }));
}
function getEventBridgeGlobal() {
    return (0, getGlobalObject_1.getGlobalObject)().DatadogEventBridge;
}
//# sourceMappingURL=eventBridge.js.map