"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.getType = getType;
/**
 * Similar to `typeof`, but distinguish plain objects from `null` and arrays
 */
function getType(value) {
    if (value === null) {
        return 'null';
    }
    if (Array.isArray(value)) {
        return 'array';
    }
    return typeof value;
}
//# sourceMappingURL=typeUtils.js.map