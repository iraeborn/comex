"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getRUMInternalContext = getRUMInternalContext;
exports.resetRUMInternalContext = resetRUMInternalContext;
var browser_core_1 = require("@datadog/browser-core");
var logsSentBeforeRumInjectionTelemetryAdded = false;
function getRUMInternalContext(startTime) {
    var browserWindow = window;
    if ((0, browser_core_1.willSyntheticsInjectRum)()) {
        var context = getInternalContextFromRumGlobal(browserWindow.DD_RUM_SYNTHETICS);
        if (!context && !logsSentBeforeRumInjectionTelemetryAdded) {
            logsSentBeforeRumInjectionTelemetryAdded = true;
            (0, browser_core_1.addTelemetryDebug)('Logs sent before RUM is injected by the synthetics worker', {
                testId: (0, browser_core_1.getSyntheticsTestId)(),
                resultId: (0, browser_core_1.getSyntheticsResultId)(),
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
function resetRUMInternalContext() {
    logsSentBeforeRumInjectionTelemetryAdded = false;
}
//# sourceMappingURL=rumInternalContext.js.map