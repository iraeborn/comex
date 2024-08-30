"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ONE_MEBI_BYTE = exports.ONE_KIBI_BYTE = void 0;
exports.computeBytesCount = computeBytesCount;
exports.concatBuffers = concatBuffers;
exports.ONE_KIBI_BYTE = 1024;
exports.ONE_MEBI_BYTE = 1024 * exports.ONE_KIBI_BYTE;
// eslint-disable-next-line no-control-regex
var HAS_MULTI_BYTES_CHARACTERS = /[^\u0000-\u007F]/;
function computeBytesCount(candidate) {
    // Accurate bytes count computations can degrade performances when there is a lot of events to process
    if (!HAS_MULTI_BYTES_CHARACTERS.test(candidate)) {
        return candidate.length;
    }
    if (window.TextEncoder !== undefined) {
        return new TextEncoder().encode(candidate).length;
    }
    return new Blob([candidate]).size;
}
function concatBuffers(buffers) {
    var length = buffers.reduce(function (total, buffer) { return total + buffer.length; }, 0);
    var result = new Uint8Array(length);
    var offset = 0;
    for (var _i = 0, buffers_1 = buffers; _i < buffers_1.length; _i++) {
        var buffer = buffers_1[_i];
        result.set(buffer, offset);
        offset += buffer.length;
    }
    return result;
}
//# sourceMappingURL=byteUtils.js.map