import { willSyntheticsInjectRum, addTelemetryDebug, getSyntheticsTestId, getSyntheticsResultId, } from '@datadog/browser-core';
var logsSentBeforeRumInjectionTelemetryAdded = false;
export function getRUMInternalContext(startTime) {
    var browserWindow = window;
    if (willSyntheticsInjectRum()) {
        var context = getInternalContextFromRumGlobal(browserWindow.DD_RUM_SYNTHETICS);
        if (!context && !logsSentBeforeRumInjectionTelemetryAdded) {
            logsSentBeforeRumInjectionTelemetryAdded = true;
            addTelemetryDebug('Logs sent before RUM is injected by the synthetics worker', {
                testId: getSyntheticsTestId(),
                resultId: getSyntheticsResultId(),
            });
        }
        return context;
    }
    return getInternalContextFromRumGlobal(browserWindow.DD_RUM);
    function getInternalContextFromRumGlobal(rumGlobal) {
        if (rumGlobal && rumGlobal.getInternalContext) {
            return rumGlobal.getInternalContext(startTime);
        }
    }
}
export function resetRUMInternalContext() {
    logsSentBeforeRumInjectionTelemetryAdded = false;
}
//# sourceMappingURL=rumInternalContext.js.map