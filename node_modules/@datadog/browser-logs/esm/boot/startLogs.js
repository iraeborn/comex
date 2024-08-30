import { sendToExtension, createPageExitObservable, willSyntheticsInjectRum, canUseEventBridge, } from '@datadog/browser-core';
import { startLogsSessionManager, startLogsSessionManagerStub } from '../domain/logsSessionManager';
import { startLogsAssembly } from '../domain/assembly';
import { startConsoleCollection } from '../domain/console/consoleCollection';
import { startReportCollection } from '../domain/report/reportCollection';
import { startNetworkErrorCollection } from '../domain/networkError/networkErrorCollection';
import { startRuntimeErrorCollection } from '../domain/runtimeError/runtimeErrorCollection';
import { LifeCycle } from '../domain/lifeCycle';
import { startLoggerCollection } from '../domain/logger/loggerCollection';
import { startLogsBatch } from '../transport/startLogsBatch';
import { startLogsBridge } from '../transport/startLogsBridge';
import { startInternalContext } from '../domain/contexts/internalContext';
import { startReportError } from '../domain/reportError';
import { startLogsTelemetry } from '../domain/logsTelemetry';
export function startLogs(initConfiguration, configuration, getCommonContext, 
// `startLogs` and its subcomponents assume tracking consent is granted initially and starts
// collecting logs unconditionally. As such, `startLogs` should be called with a
// `trackingConsentState` set to "granted".
trackingConsentState) {
    var lifeCycle = new LifeCycle();
    var cleanupTasks = [];
    lifeCycle.subscribe(1 /* LifeCycleEventType.LOG_COLLECTED */, function (log) { return sendToExtension('logs', log); });
    var reportError = startReportError(lifeCycle);
    var pageExitObservable = createPageExitObservable(configuration);
    var session = configuration.sessionStoreStrategyType && !canUseEventBridge() && !willSyntheticsInjectRum()
        ? startLogsSessionManager(configuration, trackingConsentState)
        : startLogsSessionManagerStub(configuration);
    var stopLogsTelemetry = startLogsTelemetry(initConfiguration, configuration, reportError, pageExitObservable, session).stop;
    cleanupTasks.push(function () { return stopLogsTelemetry(); });
    startNetworkErrorCollection(configuration, lifeCycle);
    startRuntimeErrorCollection(configuration, lifeCycle);
    startConsoleCollection(configuration, lifeCycle);
    startReportCollection(configuration, lifeCycle);
    var handleLog = startLoggerCollection(lifeCycle).handleLog;
    startLogsAssembly(session, configuration, lifeCycle, getCommonContext, reportError);
    if (!canUseEventBridge()) {
        var stopLogsBatch_1 = startLogsBatch(configuration, lifeCycle, reportError, pageExitObservable, session).stop;
        cleanupTasks.push(function () { return stopLogsBatch_1(); });
    }
    else {
        startLogsBridge(lifeCycle);
    }
    var internalContext = startInternalContext(session);
    return {
        handleLog: handleLog,
        getInternalContext: internalContext.get,
        stop: function () {
            cleanupTasks.forEach(function (task) { return task(); });
        },
    };
}
//# sourceMappingURL=startLogs.js.map