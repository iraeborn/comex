import { startTelemetry, canUseEventBridge, getEventBridge, startBatchWithReplica, createIdentityEncoder, isTelemetryReplicationAllowed, addTelemetryConfiguration, drainPreStartTelemetry, } from '@datadog/browser-core';
import { getRUMInternalContext } from './contexts/rumInternalContext';
import { serializeLogsConfiguration } from './configuration';
export function startLogsTelemetry(initConfiguration, configuration, reportError, pageExitObservable, session) {
    var telemetry = startTelemetry("browser-logs-sdk" /* TelemetryService.LOGS */, configuration);
    telemetry.setContextProvider(function () {
        var _a, _b, _c, _d, _e, _f;
        return ({
            application: {
                id: (_a = getRUMInternalContext()) === null || _a === void 0 ? void 0 : _a.application_id,
            },
            session: {
                id: (_b = session.findTrackedSession()) === null || _b === void 0 ? void 0 : _b.id,
            },
            view: {
                id: (_d = (_c = getRUMInternalContext()) === null || _c === void 0 ? void 0 : _c.view) === null || _d === void 0 ? void 0 : _d.id,
            },
            action: {
                id: (_f = (_e = getRUMInternalContext()) === null || _e === void 0 ? void 0 : _e.user_action) === null || _f === void 0 ? void 0 : _f.id,
            },
        });
    });
    var cleanupTasks = [];
    if (canUseEventBridge()) {
        var bridge_1 = getEventBridge();
        var telemetrySubscription_1 = telemetry.observable.subscribe(function (event) { return bridge_1.send('internal_telemetry', event); });
        cleanupTasks.push(function () { return telemetrySubscription_1.unsubscribe(); });
    }
    else {
        var telemetryBatch_1 = startBatchWithReplica(configuration, {
            endpoint: configuration.rumEndpointBuilder,
            encoder: createIdentityEncoder(),
        }, configuration.replica && {
            endpoint: configuration.replica.rumEndpointBuilder,
            encoder: createIdentityEncoder(),
        }, reportError, pageExitObservable, session.expireObservable);
        cleanupTasks.push(function () { return telemetryBatch_1.stop(); });
        var telemetrySubscription_2 = telemetry.observable.subscribe(function (event) {
            return telemetryBatch_1.add(event, isTelemetryReplicationAllowed(configuration));
        });
        cleanupTasks.push(function () { return telemetrySubscription_2.unsubscribe(); });
    }
    drainPreStartTelemetry();
    addTelemetryConfiguration(serializeLogsConfiguration(initConfiguration));
    return {
        telemetry: telemetry,
        stop: function () {
            cleanupTasks.forEach(function (task) { return task(); });
        },
    };
}
//# sourceMappingURL=logsTelemetry.js.map