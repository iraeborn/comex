"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.startRuntimeErrorCollection = startRuntimeErrorCollection;
var browser_core_1 = require("@datadog/browser-core");
var isAuthorized_1 = require("../logger/isAuthorized");
function startRuntimeErrorCollection(configuration, lifeCycle) {
    if (!configuration.forwardErrorsToLogs) {
        return { stop: browser_core_1.noop };
    }
    var rawErrorObservable = new browser_core_1.Observable();
    var stopRuntimeErrorTracking = (0, browser_core_1.trackRuntimeError)(rawErrorObservable).stop;
    var rawErrorSubscription = rawErrorObservable.subscribe(function (rawError) {
        lifeCycle.notify(0 /* LifeCycleEventType.RAW_LOG_COLLECTED */, {
            rawLogsEvent: {
                message: rawError.message,
                date: rawError.startClocks.timeStamp,
                error: {
                    kind: rawError.type,
                    stack: rawError.stack,
                    causes: rawError.causes,
                },
                origin: browser_core_1.ErrorSource.SOURCE,
                status: isAuthorized_1.StatusType.error,
            },
        });
    });
    return {
        stop: function () {
            stopRuntimeErrorTracking();
            rawErrorSubscription.unsubscribe();
        },
    };
}
//# sourceMappingURL=runtimeErrorCollection.js.map