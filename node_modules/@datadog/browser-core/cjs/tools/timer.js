"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.setTimeout = setTimeout;
exports.clearTimeout = clearTimeout;
exports.setInterval = setInterval;
exports.clearInterval = clearInterval;
var getZoneJsOriginalValue_1 = require("./getZoneJsOriginalValue");
var monitor_1 = require("./monitor");
var getGlobalObject_1 = require("./getGlobalObject");
function setTimeout(callback, delay) {
    return (0, getZoneJsOriginalValue_1.getZoneJsOriginalValue)((0, getGlobalObject_1.getGlobalObject)(), 'setTimeout')((0, monitor_1.monitor)(callback), delay);
}
function clearTimeout(timeoutId) {
    (0, getZoneJsOriginalValue_1.getZoneJsOriginalValue)((0, getGlobalObject_1.getGlobalObject)(), 'clearTimeout')(timeoutId);
}
function setInterval(callback, delay) {
    return (0, getZoneJsOriginalValue_1.getZoneJsOriginalValue)((0, getGlobalObject_1.getGlobalObject)(), 'setInterval')((0, monitor_1.monitor)(callback), delay);
}
function clearInterval(timeoutId) {
    (0, getZoneJsOriginalValue_1.getZoneJsOriginalValue)((0, getGlobalObject_1.getGlobalObject)(), 'clearInterval')(timeoutId);
}
//# sourceMappingURL=timer.js.map