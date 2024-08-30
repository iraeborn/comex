import { addTelemetryUsage, assign, createContextManager, makePublicApi, monitor, checkUser, sanitizeUser, sanitize, createCustomerDataTrackerManager, storeContextManager, displayAlreadyInitializedError, deepClone, createTrackingConsentState, } from '@datadog/browser-core';
import { Logger } from '../domain/logger';
import { buildCommonContext } from '../domain/contexts/commonContext';
import { createPreStartStrategy } from './preStartLogs';
var LOGS_STORAGE_KEY = 'logs';
export function makeLogsPublicApi(startLogsImpl) {
    var customerDataTrackerManager = createCustomerDataTrackerManager();
    var globalContextManager = createContextManager(customerDataTrackerManager.getOrCreateTracker(2 /* CustomerDataType.GlobalContext */));
    var userContextManager = createContextManager(customerDataTrackerManager.getOrCreateTracker(1 /* CustomerDataType.User */));
    var trackingConsentState = createTrackingConsentState();
    function getCommonContext() {
        return buildCommonContext(globalContextManager, userContextManager);
    }
    var strategy = createPreStartStrategy(getCommonContext, trackingConsentState, function (initConfiguration, configuration) {
        if (initConfiguration.storeContextsAcrossPages) {
            storeContextManager(configuration, globalContextManager, LOGS_STORAGE_KEY, 2 /* CustomerDataType.GlobalContext */);
            storeContextManager(configuration, userContextManager, LOGS_STORAGE_KEY, 1 /* CustomerDataType.User */);
        }
        var startLogsResult = startLogsImpl(initConfiguration, configuration, getCommonContext, trackingConsentState);
        strategy = createPostStartStrategy(initConfiguration, startLogsResult);
        return startLogsResult;
    });
    var customLoggers = {};
    var mainLogger = new Logger(function () {
        var params = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            params[_i] = arguments[_i];
        }
        return strategy.handleLog.apply(strategy, params);
    }, customerDataTrackerManager.createDetachedTracker());
    return makePublicApi({
        logger: mainLogger,
        init: monitor(function (initConfiguration) { return strategy.init(initConfiguration); }),
        setTrackingConsent: monitor(function (trackingConsent) {
            trackingConsentState.update(trackingConsent);
            addTelemetryUsage({ feature: 'set-tracking-consent', tracking_consent: trackingConsent });
        }),
        getGlobalContext: monitor(function () { return globalContextManager.getContext(); }),
        setGlobalContext: monitor(function (context) { return globalContextManager.setContext(context); }),
        setGlobalContextProperty: monitor(function (key, value) { return globalContextManager.setContextProperty(key, value); }),
        removeGlobalContextProperty: monitor(function (key) { return globalContextManager.removeContextProperty(key); }),
        clearGlobalContext: monitor(function () { return globalContextManager.clearContext(); }),
        createLogger: monitor(function (name, conf) {
            if (conf === void 0) { conf = {}; }
            customLoggers[name] = new Logger(function () {
                var params = [];
                for (var _i = 0; _i < arguments.length; _i++) {
                    params[_i] = arguments[_i];
                }
                return strategy.handleLog.apply(strategy, params);
            }, customerDataTrackerManager.createDetachedTracker(), sanitize(name), conf.handler, conf.level, sanitize(conf.context));
            return customLoggers[name];
        }),
        getLogger: monitor(function (name) { return customLoggers[name]; }),
        getInitConfiguration: monitor(function () { return deepClone(strategy.initConfiguration); }),
        getInternalContext: monitor(function (startTime) { return strategy.getInternalContext(startTime); }),
        setUser: monitor(function (newUser) {
            if (checkUser(newUser)) {
                userContextManager.setContext(sanitizeUser(newUser));
            }
        }),
        getUser: monitor(function () { return userContextManager.getContext(); }),
        setUserProperty: monitor(function (key, property) {
            var _a;
            var sanitizedProperty = sanitizeUser((_a = {}, _a[key] = property, _a))[key];
            userContextManager.setContextProperty(key, sanitizedProperty);
        }),
        removeUserProperty: monitor(function (key) { return userContextManager.removeContextProperty(key); }),
        clearUser: monitor(function () { return userContextManager.clearContext(); }),
    });
}
function createPostStartStrategy(initConfiguration, startLogsResult) {
    return assign({
        init: function (initConfiguration) {
            displayAlreadyInitializedError('DD_LOGS', initConfiguration);
        },
        initConfiguration: initConfiguration,
    }, startLogsResult);
}
//# sourceMappingURL=logsPublicApi.js.map