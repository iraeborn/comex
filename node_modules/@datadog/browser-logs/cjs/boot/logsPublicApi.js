"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.makeLogsPublicApi = makeLogsPublicApi;
var browser_core_1 = require("@datadog/browser-core");
var logger_1 = require("../domain/logger");
var commonContext_1 = require("../domain/contexts/commonContext");
var preStartLogs_1 = require("./preStartLogs");
var LOGS_STORAGE_KEY = 'logs';
function makeLogsPublicApi(startLogsImpl) {
    var customerDataTrackerManager = (0, browser_core_1.createCustomerDataTrackerManager)();
    var globalContextManager = (0, browser_core_1.createContextManager)(customerDataTrackerManager.getOrCreateTracker(2 /* CustomerDataType.GlobalContext */));
    var userContextManager = (0, browser_core_1.createContextManager)(customerDataTrackerManager.getOrCreateTracker(1 /* CustomerDataType.User */));
    var trackingConsentState = (0, browser_core_1.createTrackingConsentState)();
    function getCommonContext() {
        return (0, commonContext_1.buildCommonContext)(globalContextManager, userContextManager);
    }
    var strategy = (0, preStartLogs_1.createPreStartStrategy)(getCommonContext, trackingConsentState, function (initConfiguration, configuration) {
        if (initConfiguration.storeContextsAcrossPages) {
            (0, browser_core_1.storeContextManager)(configuration, globalContextManager, LOGS_STORAGE_KEY, 2 /* CustomerDataType.GlobalContext */);
            (0, browser_core_1.storeContextManager)(configuration, userContextManager, LOGS_STORAGE_KEY, 1 /* CustomerDataType.User */);
        }
        var startLogsResult = startLogsImpl(initConfiguration, configuration, getCommonContext, trackingConsentState);
        strategy = createPostStartStrategy(initConfiguration, startLogsResult);
        return startLogsResult;
    });
    var customLoggers = {};
    var mainLogger = new logger_1.Logger(function () {
        var params = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            params[_i] = arguments[_i];
        }
        return strategy.handleLog.apply(strategy, params);
    }, customerDataTrackerManager.createDetachedTracker());
    return (0, browser_core_1.makePublicApi)({
        logger: mainLogger,
        init: (0, browser_core_1.monitor)(function (initConfiguration) { return strategy.init(initConfiguration); }),
        setTrackingConsent: (0, browser_core_1.monitor)(function (trackingConsent) {
            trackingConsentState.update(trackingConsent);
            (0, browser_core_1.addTelemetryUsage)({ feature: 'set-tracking-consent', tracking_consent: trackingConsent });
        }),
        getGlobalContext: (0, browser_core_1.monitor)(function () { return globalContextManager.getContext(); }),
        setGlobalContext: (0, browser_core_1.monitor)(function (context) { return globalContextManager.setContext(context); }),
        setGlobalContextProperty: (0, browser_core_1.monitor)(function (key, value) { return globalContextManager.setContextProperty(key, value); }),
        removeGlobalContextProperty: (0, browser_core_1.monitor)(function (key) { return globalContextManager.removeContextProperty(key); }),
        clearGlobalContext: (0, browser_core_1.monitor)(function () { return globalContextManager.clearContext(); }),
        createLogger: (0, browser_core_1.monitor)(function (name, conf) {
            if (conf === void 0) { conf = {}; }
            customLoggers[name] = new logger_1.Logger(function () {
                var params = [];
                for (var _i = 0; _i < arguments.length; _i++) {
                    params[_i] = arguments[_i];
                }
                return strategy.handleLog.apply(strategy, params);
            }, customerDataTrackerManager.createDetachedTracker(), (0, browser_core_1.sanitize)(name), conf.handler, conf.level, (0, browser_core_1.sanitize)(conf.context));
            return customLoggers[name];
        }),
        getLogger: (0, browser_core_1.monitor)(function (name) { return customLoggers[name]; }),
        getInitConfiguration: (0, browser_core_1.monitor)(function () { return (0, browser_core_1.deepClone)(strategy.initConfiguration); }),
        getInternalContext: (0, browser_core_1.monitor)(function (startTime) { return strategy.getInternalContext(startTime); }),
        setUser: (0, browser_core_1.monitor)(function (newUser) {
            if ((0, browser_core_1.checkUser)(newUser)) {
                userContextManager.setContext((0, browser_core_1.sanitizeUser)(newUser));
            }
        }),
        getUser: (0, browser_core_1.monitor)(function () { return userContextManager.getContext(); }),
        setUserProperty: (0, browser_core_1.monitor)(function (key, property) {
            var _a;
            var sanitizedProperty = (0, browser_core_1.sanitizeUser)((_a = {}, _a[key] = property, _a))[key];
            userContextManager.setContextProperty(key, sanitizedProperty);
        }),
        removeUserProperty: (0, browser_core_1.monitor)(function (key) { return userContextManager.removeContextProperty(key); }),
        clearUser: (0, browser_core_1.monitor)(function () { return userContextManager.clearContext(); }),
    });
}
function createPostStartStrategy(initConfiguration, startLogsResult) {
    return (0, browser_core_1.assign)({
        init: function (initConfiguration) {
            (0, browser_core_1.displayAlreadyInitializedError)('DD_LOGS', initConfiguration);
        },
        initConfiguration: initConfiguration,
    }, startLogsResult);
}
//# sourceMappingURL=logsPublicApi.js.map