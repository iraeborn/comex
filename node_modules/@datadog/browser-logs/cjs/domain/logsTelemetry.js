"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.startLogsTelemetry = startLogsTelemetry;
var browser_core_1 = require("@datadog/browser-core");
var rumInternalContext_1 = require("./contexts/rumInternalContext");
var configuration_1 = require("./configuration");
function startLogsTelemetry(initConfiguration, configuration, reportError, pageExitObservable, session) {
    var telemetry = (0, browser_core_1.startTelemetry)("browser-logs-sdk" /* TelemetryService.LOGS */, configuration);
    telemetry.setContextProvider(function () {
        var _a, _b, _c, _d, _e, _f;
        return ({
            application: {
                id: (_a = (0, rumInternalContext_1.getRUMInternalContext)()) === null || _a === void 0 ? void 0 : _a.application_id,
            },
            session: {
                id: (_b = session.findTrackedSession()) === null || _b === void 0 ? void 0 : _b.id,
            },
            view: {
                id: (_d = (_c = (0, rumInternalContext_1.getRUMInternalContext)()) === null || _c === void 0 ? void 0 : _c.view) === null || _d === void 0 ? void 0 : _d.id,
            },
            action: {
                id: (_f = (_e = (0, rumInternalContext_1.getRUMInternalContext)()) === null || _e === void 0 ? void 0 : _e.user_action) === null || _f === void 0 ? void 0 : _f.id,
            },
        });
    });
    var cleanupTasks = [];
    if ((0, browser_core_1.canUseEventBridge)()) {
        var bridge_1 = (0, browser_core_1.getEventBridge)();
        var telemetrySubscription_1 = telemetry.observable.subscribe(function (event) { return bridge_1.send('internal_telemetry', event); });
        cleanupTasks.push(function () { return telemetrySubscription_1.unsubscribe(); });
    }
    else {
        var telemetryBatch_1 = (0, browser_core_1.startBatchWithReplica)(configuration, {
            endpoint: configuration.rumEndpointBuilder,
            encoder: (0, browser_core_1.createIdentityEncoder)(),
        }, configuration.replica && {
            endpoint: configuration.replica.rumEndpointBuilder,
            encoder: (0, browser_core_1.createIdentityEncoder)(),
        }, reportError, pageExitObservable, session.expireObservable);
        cleanupTasks.push(function () { return telemetryBatch_1.stop(); });
        var telemetrySubscription_2 = telemetry.observable.subscribe(function (event) {
            return telemetryBatch_1.add(event, (0, browser_core_1.isTelemetryReplicationAllowed)(configuration));
        });
        cleanupTasks.push(function () { return telemetrySubscription_2.unsubscribe(); });
    }
    (0, browser_core_1.drainPreStartTelemetry)();
    (0, browser_core_1.addTelemetryConfiguration)((0, configuration_1.serializeLogsConfiguration)(initConfiguration));
    return {
        telemetry: telemetry,
        stop: function () {
            cleanupTasks.forEach(function (task) { return task(); });
        },
    };
}
//# sourceMappingURL=logsTelemetry.js.map