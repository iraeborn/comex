var _a;
import { includes } from '@datadog/browser-core';
export function isAuthorized(status, handlerType, logger) {
    var loggerHandler = logger.getHandler();
    var sanitizedHandlerType = Array.isArray(loggerHandler) ? loggerHandler : [loggerHandler];
    return (STATUS_PRIORITIES[status] >= STATUS_PRIORITIES[logger.getLevel()] && includes(sanitizedHandlerType, handlerType));
}
export var StatusType = {
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
export var STATUS_PRIORITIES = (_a = {},
    _a[StatusType.ok] = 0,
    _a[StatusType.debug] = 1,
    _a[StatusType.info] = 2,
    _a[StatusType.notice] = 4,
    _a[StatusType.warn] = 5,
    _a[StatusType.error] = 6,
    _a[StatusType.critical] = 7,
    _a[StatusType.alert] = 8,
    _a[StatusType.emerg] = 9,
    _a);
//# sourceMappingURL=isAuthorized.js.map