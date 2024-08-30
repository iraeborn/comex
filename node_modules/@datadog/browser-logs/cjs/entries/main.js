"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.datadogLogs = exports.StatusType = exports.HandlerType = exports.Logger = void 0;
var browser_core_1 = require("@datadog/browser-core");
var logsPublicApi_1 = require("../boot/logsPublicApi");
var startLogs_1 = require("../boot/startLogs");
var logger_1 = require("../domain/logger");
Object.defineProperty(exports, "Logger", { enumerable: true, get: function () { return logger_1.Logger; } });
Object.defineProperty(exports, "HandlerType", { enumerable: true, get: function () { return logger_1.HandlerType; } });
var isAuthorized_1 = require("../domain/logger/isAuthorized");
Object.defineProperty(exports, "StatusType", { enumerable: true, get: function () { return isAuthorized_1.StatusType; } });
exports.datadogLogs = (0, logsPublicApi_1.makeLogsPublicApi)(startLogs_1.startLogs);
(0, browser_core_1.defineGlobal)((0, browser_core_1.getGlobalObject)(), 'DD_LOGS', exports.datadogLogs);
//# sourceMappingURL=main.js.map