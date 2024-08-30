import { startsWith } from './utils/polyfills';
import { display } from './display';
import { getType } from './utils/typeUtils';
export function isMatchOption(item) {
    var itemType = getType(item);
    return itemType === 'string' || itemType === 'function' || item instanceof RegExp;
}
/**
 * Returns true if value can be matched by at least one of the provided MatchOptions.
 * When comparing strings, setting useStartsWith to true will compare the value with the start of
 * the option, instead of requiring an exact match.
 */
export function matchList(list, value, useStartsWith) {
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
                return useStartsWith ? startsWith(value, item) : item === value;
            }
        }
        catch (e) {
            display.error(e);
        }
        return false;
    });
}
//# sourceMappingURL=matchOption.js.map