"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.startLogs = startLogs;
var browser_core_1 = require("@datadog/browser-core");
var logsSessionManager_1 = require("../domain/logsSessionManager");
var assembly_1 = require("../domain/assembly");
var consoleCollection_1 = require("../domain/console/consoleCollection");
var reportCollection_1 = require("../domain/report/reportCollection");
var networkErrorCollection_1 = require("../domain/networkError/networkErrorCollection");
var runtimeErrorCollection_1 = require("../domain/runtimeError/runtimeErrorCollection");
var lifeCycle_1 = require("../domain/lifeCycle");
var loggerCollection_1 = require("../domain/logger/loggerCollection");
var startLogsBatch_1 = require("../transport/startLogsBatch");
var startLogsBridge_1 = require("../transport/startLogsBridge");
var internalContext_1 = require("../domain/contexts/internalContext");
var reportError_1 = require("../domain/reportError");
var logsTelemetry_1 = require("../domain/logsTelemetry");
function startLogs(initConfiguration, configuration, getCommonContext, 
// `startLogs` and its subcomponents assume tracking consent is granted initially and starts
// collecting logs unconditionally. As such, `startLogs` should be called with a
// `trackingConsentState` set to "granted".
trackingConsentState) {
    var lifeCycle = new lifeCycle_1.LifeCycle();
    var cleanupTasks = [];
    lifeCycle.subscribe(1 /* LifeCycleEventType.LOG_COLLECTED */, function (log) { return (0, browser_core_1.sendToExtension)('logs', log); });
    var reportError = (0, reportError_1.startReportError)(lifeCycle);
    var pageExitObservable = (0, browser_core_1.createPageExitObservable)(configuration);
    var session = configuration.sessionStoreStrategyType && !(0, browser_core_1.canUseEventBridge)() && !(0, browser_core_1.willSyntheticsInjectRum)()
        ? (0, logsSessionManager_1.startLogsSessionManager)(configuration, trackingConsentState)
        : (0, logsSessionManager_1.startLogsSessionManagerStub)(configuration);
    var stopLogsTelemetry = (0, logsTelemetry_1.startLogsTelemetry)(initConfiguration, configuration, reportError, pageExitObservable, session).stop;
    cleanupTasks.push(function () { return stopLogsTelemetry(); });
    (0, networkErrorCollection_1.startNetworkErrorCollection)(configuration, lifeCycle);
    (0, runtimeErrorCollection_1.startRuntimeErrorCollection)(configuration, lifeCycle);
    (0, consoleCollection_1.startConsoleCollection)(configuration, lifeCycle);
    (0, reportCollection_1.startReportCollection)(configuration, lifeCycle);
    var handleLog = (0, loggerCollection_1.startLoggerCollection)(lifeCycle).handleLog;
    (0, assembly_1.startLogsAssembly)(session, configuration, lifeCycle, getCommonContext, reportError);
    if (!(0, browser_core_1.canUseEventBridge)()) {
        var stopLogsBatch_1 = (0, startLogsBatch_1.startLogsBatch)(configuration, lifeCycle, reportError, pageExitObservable, session).stop;
        cleanupTasks.push(function () { return stopLogsBatch_1(); });
    }
    else {
        (0, startLogsBridge_1.startLogsBridge)(lifeCycle);
    }
    var internalContext = (0, internalContext_1.startInternalContext)(session);
    return {
        handleLog: handleLog,
        getInternalContext: internalContext.get,
        stop: function () {
            cleanupTasks.forEach(function (task) { return task(); });
        },
    };
}
//# sourceMappingURL=startLogs.js.map