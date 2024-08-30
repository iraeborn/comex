"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.isMatchOption = isMatchOption;
exports.matchList = matchList;
var polyfills_1 = require("./utils/polyfills");
var display_1 = require("./display");
var typeUtils_1 = require("./utils/typeUtils");
function isMatchOption(item) {
    var itemType = (0, typeUtils_1.getType)(item);
    return itemType === 'string' || itemType === 'function' || item instanceof RegExp;
}
/**
 * Returns true if value can be matched by at least one of the provided MatchOptions.
 * When comparing strings, setting useStartsWith to true will compare the value with the start of
 * the option, instead of requiring an exact match.
 */
function matchList(list, value, useStartsWith) {
    if (useStartsWith === void 0) { useStartsWith = false; }
    return list.some(function (item) {
        try {
            if (typeof item === 'function') {
                return item(value);
            }
            else if (item instanceof RegExp) {
                return item.test(value);
            }
            else if (typeof item === 'string') {
                return useStartsWith ? (0, polyfills_1.startsWith)(value, item) : item === value;
            }
        }
        catch (e) {
            display_1.display.error(e);
        }
        return false;
    });
}
//# sourceMappingURL=matchOption.js.map