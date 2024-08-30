"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.SYNTHETICS_INJECTS_RUM_COOKIE_NAME = exports.SYNTHETICS_RESULT_ID_COOKIE_NAME = exports.SYNTHETICS_TEST_ID_COOKIE_NAME = void 0;
exports.willSyntheticsInjectRum = willSyntheticsInjectRum;
exports.getSyntheticsTestId = getSyntheticsTestId;
exports.getSyntheticsResultId = getSyntheticsResultId;
var cookie_1 = require("../../browser/cookie");
exports.SYNTHETICS_TEST_ID_COOKIE_NAME = 'datadog-synthetics-public-id';
exports.SYNTHETICS_RESULT_ID_COOKIE_NAME = 'datadog-synthetics-result-id';
exports.SYNTHETICS_INJECTS_RUM_COOKIE_NAME = 'datadog-synthetics-injects-rum';
function willSyntheticsInjectRum() {
    return Boolean(window._DATADOG_SYNTHETICS_INJECTS_RUM || (0, cookie_1.getInitCookie)(exports.SYNTHETICS_INJECTS_RUM_COOKIE_NAME));
}
function getSyntheticsTestId() {
    var value = window._DATADOG_SYNTHETICS_PUBLIC_ID || (0, cookie_1.getInitCookie)(exports.SYNTHETICS_TEST_ID_COOKIE_NAME);
    return typeof value === 'string' ? value : undefined;
}
function getSyntheticsResultId() {
    var value = window._DATADOG_SYNTHETICS_RESULT_ID || (0, cookie_1.getInitCookie)(exports.SYNTHETICS_RESULT_ID_COOKIE_NAME);
    return typeof value === 'string' ? value : undefined;
}
//# sourceMappingURL=syntheticsWorkerValues.js.map