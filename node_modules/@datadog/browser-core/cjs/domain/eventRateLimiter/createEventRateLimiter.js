"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.createEventRateLimiter = createEventRateLimiter;
var timer_1 = require("../../tools/timer");
var timeUtils_1 = require("../../tools/utils/timeUtils");
var error_types_1 = require("../error/error.types");
function createEventRateLimiter(eventType, limit, onLimitReached) {
    var eventCount = 0;
    var allowNextEvent = false;
    return {
        isLimitReached: function () {
            if (eventCount === 0) {
                (0, timer_1.setTimeout)(function () {
                    eventCount = 0;
                }, timeUtils_1.ONE_MINUTE);
            }
            eventCount += 1;
            if (eventCount <= limit || allowNextEvent) {
                allowNextEvent = false;
                return false;
            }
            if (eventCount === limit + 1) {
                allowNextEvent = true;
                try {
                    onLimitReached({
                        message: "Reached max number of ".concat(eventType, "s by minute: ").concat(limit),
                        source: error_types_1.ErrorSource.AGENT,
                        startClocks: (0, timeUtils_1.clocksNow)(),
                    });
                }
                finally {
                    allowNextEvent = false;
                }
            }
            return true;
        },
    };
}
//# sourceMappingURL=createEventRateLimiter.js.map