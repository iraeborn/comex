export function getConnectivity() {
    var _a;
    var navigator = window.navigator;
    return {
        status: navigator.onLine ? 'connected' : 'not_connected',
        interfaces: navigator.connection && navigator.connection.type ? [navigator.connection.type] : undefined,
        effective_type: (_a = navigator.connection) === null || _a === void 0 ? void 0 : _a.effectiveType,
    };
}
//# sourceMappingURL=connectivity.js.map