"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.generateUUID = generateUUID;
exports.findCommaSeparatedValue = findCommaSeparatedValue;
exports.findCommaSeparatedValues = findCommaSeparatedValues;
exports.safeTruncate = safeTruncate;
/**
 * UUID v4
 * from https://gist.github.com/jed/982883
 */
function generateUUID(placeholder) {
    return placeholder
        ? // eslint-disable-next-line  no-bitwise
            (parseInt(placeholder, 10) ^ ((Math.random() * 16) >> (parseInt(placeholder, 10) / 4))).toString(16)
        : "".concat(1e7, "-").concat(1e3, "-").concat(4e3, "-").concat(8e3, "-").concat(1e11).replace(/[018]/g, generateUUID);
}
var COMMA_SEPARATED_KEY_VALUE = /([\w-]+)\s*=\s*([^;]+)/g;
function findCommaSeparatedValue(rawString, name) {
    COMMA_SEPARATED_KEY_VALUE.lastIndex = 0;
    // eslint-disable-next-line no-constant-condition
    while (true) {
        var match = COMMA_SEPARATED_KEY_VALUE.exec(rawString);
        if (match) {
            if (match[1] === name) {
                return match[2];
            }
        }
        else {
            break;
        }
    }
}
function findCommaSeparatedValues(rawString) {
    var result = new Map();
    COMMA_SEPARATED_KEY_VALUE.lastIndex = 0;
    // eslint-disable-next-line no-constant-condition
    while (true) {
        var match = COMMA_SEPARATED_KEY_VALUE.exec(rawString);
        if (match) {
            result.set(match[1], match[2]);
        }
        else {
            break;
        }
    }
    return result;
}
function safeTruncate(candidate, length, suffix) {
    if (suffix === void 0) { suffix = ''; }
    var lastChar = candidate.charCodeAt(length - 1);
    var isLastCharSurrogatePair = lastChar >= 0xd800 && lastChar <= 0xdbff;
    var correctedLength = isLastCharSurrogatePair ? length + 1 : length;
    if (candidate.length <= correctedLength) {
        return candidate;
    }
    return "".concat(candidate.slice(0, correctedLength)).concat(suffix);
}
//# sourceMappingURL=stringUtils.js.map