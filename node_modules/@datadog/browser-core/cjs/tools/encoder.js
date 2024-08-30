"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.createIdentityEncoder = createIdentityEncoder;
var byteUtils_1 = require("./utils/byteUtils");
function createIdentityEncoder() {
    var output = '';
    var outputBytesCount = 0;
    return {
        isAsync: false,
        get isEmpty() {
            return !output;
        },
        write: function (data, callback) {
            var additionalEncodedBytesCount = (0, byteUtils_1.computeBytesCount)(data);
            outputBytesCount += additionalEncodedBytesCount;
            output += data;
            if (callback) {
                callback(additionalEncodedBytesCount);
            }
        },
        finish: function (callback) {
            callback(this.finishSync());
        },
        finishSync: function () {
            var result = {
                output: output,
                outputBytesCount: outputBytesCount,
                rawBytesCount: outputBytesCount,
                pendingData: '',
            };
            output = '';
            outputBytesCount = 0;
            return result;
        },
        estimateEncodedBytesCount: function (data) {
            return data.length;
        },
    };
}
//# sourceMappingURL=encoder.js.map