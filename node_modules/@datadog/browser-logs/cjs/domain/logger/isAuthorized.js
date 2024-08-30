"use strict";
var _a;
Object.defineProperty(exports, "__esModule", { value: true });
exports.STATUS_PRIORITIES = exports.StatusType = void 0;
exports.isAuthorized = isAuthorized;
var browser_core_1 = require("@datadog/browser-core");
function isAuthorized(status, handlerType, logger) {
    var loggerHandler = logger.getHandler();
    var sanitizedHandlerType = Array.isArray(loggerHandler) ? loggerHandler : [loggerHandler];
    return (exports.STATUS_PRIORITIES[status] >= exports.STATUS_PRIORITIES[logger.getLevel()] && (0, browser_core_1.includes)(sanitizedHandlerType, handlerType));
}
exports.StatusType = {
    ok: 'ok',
    debug: 'debug',
    info: 'info',
    notice: 'notice',
    warn: 'warn',
    error: 'error',
    critical: 'critical',
    alert: 'alert',
    emerg: 'emerg',
};
exports.STATUS_PRIORITIES = (_a = {},
    _a[exports.StatusType.ok] = 0,
    _a[exports.StatusType.debug] = 1,
    _a[exports.StatusType.info] = 2,
    _a[exports.StatusType.notice] = 4,
    _a[exports.StatusType.warn] = 5,
    _a[exports.StatusType.error] = 6,
    _a[exports.StatusType.critical] = 7,
    _a[exports.StatusType.alert] = 8,
    _a[exports.StatusType.emerg] = 9,
    _a);
//# sourceMappingURL=isAuthorized.js.map