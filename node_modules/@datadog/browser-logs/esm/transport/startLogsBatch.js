import { createIdentityEncoder, startBatchWithReplica } from '@datadog/browser-core';
export function startLogsBatch(configuration, lifeCycle, reportError, pageExitObservable, session) {
    var batch = startBatchWithReplica(configuration, {
        endpoint: configuration.logsEndpointBuilder,
        encoder: createIdentityEncoder(),
    }, configuration.replica && {
        endpoint: configuration.replica.logsEndpointBuilder,
        encoder: createIdentityEncoder(),
    }, reportError, pageExitObservable, session.expireObservable);
    lifeCycle.subscribe(1 /* LifeCycleEventType.LOG_COLLECTED */, function (serverLogsEvent) {
        batch.add(serverLogsEvent);
    });
    return batch;
}
//# sourceMappingURL=startLogsBatch.js.map