"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.createFlushController = exports.startBatchWithReplica = exports.getEventBridge = exports.bridgeSupports = exports.canUseEventBridge = exports.createHttpRequest = void 0;
var httpRequest_1 = require("./httpRequest");
Object.defineProperty(exports, "createHttpRequest", { enumerable: true, get: function () { return httpRequest_1.createHttpRequest; } });
var eventBridge_1 = require("./eventBridge");
Object.defineProperty(exports, "canUseEventBridge", { enumerable: true, get: function () { return eventBridge_1.canUseEventBridge; } });
Object.defineProperty(exports, "bridgeSupports", { enumerable: true, get: function () { return eventBridge_1.bridgeSupports; } });
Object.defineProperty(exports, "getEventBridge", { enumerable: true, get: function () { return eventBridge_1.getEventBridge; } });
var startBatchWithReplica_1 = require("./startBatchWithReplica");
Object.defineProperty(exports, "startBatchWithReplica", { enumerable: true, get: function () { return startBatchWithReplica_1.startBatchWithReplica; } });
var flushController_1 = require("./flushController");
Object.defineProperty(exports, "createFlushController", { enumerable: true, get: function () { return flushController_1.createFlushController; } });
//# sourceMappingURL=index.js.map