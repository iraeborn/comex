"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.startLogsBatch = startLogsBatch;
var browser_core_1 = require("@datadog/browser-core");
function startLogsBatch(configuration, lifeCycle, reportError, pageExitObservable, session) {
    var batch = (0, browser_core_1.startBatchWithReplica)(configuration, {
        endpoint: configuration.logsEndpointBuilder,
        encoder: (0, browser_core_1.createIdentityEncoder)(),
    }, configuration.replica && {
        endpoint: configuration.replica.logsEndpointBuilder,
        encoder: (0, browser_core_1.createIdentityEncoder)(),
    }, reportError, pageExitObservable, session.expireObservable);
    lifeCycle.subscribe(1 /* LifeCycleEventType.LOG_COLLECTED */, function (serverLogsEvent) {
        batch.add(serverLogsEvent);
    });
    return batch;
}
//# sourceMappingURL=startLogsBatch.js.map