import { endsWith, includes } from '../tools/utils/polyfills';
import { getGlobalObject } from '../tools/getGlobalObject';
export function getEventBridge() {
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
export function bridgeSupports(capability) {
    var bridge = getEventBridge();
    return !!bridge && includes(bridge.getCapabilities(), capability);
}
export function canUseEventBridge(currentHost) {
    var _a;
    if (currentHost === void 0) { currentHost = (_a = getGlobalObject().location) === null || _a === void 0 ? void 0 : _a.hostname; }
    var bridge = getEventBridge();
    return (!!bridge &&
        bridge
            .getAllowedWebViewHosts()
            .some(function (allowedHost) { return currentHost === allowedHost || endsWith(currentHost, ".".concat(allowedHost)); }));
}
function getEventBridgeGlobal() {
    return getGlobalObject().DatadogEventBridge;
}
//# sourceMappingURL=eventBridge.js.map