"use strict";
var __spreadArray = (this && this.__spreadArray) || function (to, from, pack) {
    if (pack || arguments.length === 2) for (var i = 0, l = from.length, ar; i < l; i++) {
        if (ar || !(i in from)) {
            if (!ar) ar = Array.prototype.slice.call(from, 0, i);
            ar[i] = from[i];
        }
    }
    return to.concat(ar || Array.prototype.slice.call(from));
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.startMonitorErrorCollection = startMonitorErrorCollection;
exports.setDebugMode = setDebugMode;
exports.resetMonitor = resetMonitor;
exports.monitored = monitored;
exports.monitor = monitor;
exports.callMonitored = callMonitored;
exports.displayIfDebugEnabled = displayIfDebugEnabled;
var display_1 = require("./display");
var onMonitorErrorCollected;
var debugMode = false;
function startMonitorErrorCollection(newOnMonitorErrorCollected) {
    onMonitorErrorCollected = newOnMonitorErrorCollected;
}
function setDebugMode(newDebugMode) {
    debugMode = newDebugMode;
}
function resetMonitor() {
    onMonitorErrorCollected = undefined;
    debugMode = false;
}
function monitored(_, __, descriptor) {
    var originalMethod = descriptor.value;
    descriptor.value = function () {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
            args[_i] = arguments[_i];
        }
        var decorated = onMonitorErrorCollected ? monitor(originalMethod) : originalMethod;
        return decorated.apply(this, args);
    };
}
function monitor(fn) {
    return function () {
        // eslint-disable-next-line @typescript-eslint/no-unsafe-return
        return callMonitored(fn, this, arguments);
    }; // consider output type has input type
}
function callMonitored(fn, context, args) {
    try {
        // eslint-disable-next-line @typescript-eslint/no-unsafe-return
        return fn.apply(context, args);
    }
    catch (e) {
        displayIfDebugEnabled(e);
        if (onMonitorErrorCollected) {
            try {
                onMonitorErrorCollected(e);
            }
            catch (e) {
                displayIfDebugEnabled(e);
            }
        }
    }
}
function displayIfDebugEnabled() {
    var args = [];
    for (var _i = 0; _i < arguments.length; _i++) {
        args[_i] = arguments[_i];
    }
    if (debugMode) {
        display_1.display.error.apply(display_1.display, __spreadArray(['[MONITOR]'], args, false));
    }
}
//# sourceMappingURL=monitor.js.map