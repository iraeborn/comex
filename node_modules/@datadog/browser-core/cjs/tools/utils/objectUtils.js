"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.shallowClone = shallowClone;
exports.objectHasValue = objectHasValue;
exports.isEmptyObject = isEmptyObject;
exports.mapValues = mapValues;
var polyfills_1 = require("./polyfills");
function shallowClone(object) {
    return (0, polyfills_1.assign)({}, object);
}
function objectHasValue(object, value) {
    return Object.keys(object).some(function (key) { return object[key] === value; });
}
function isEmptyObject(object) {
    return Object.keys(object).length === 0;
}
function mapValues(object, fn) {
    var newObject = {};
    for (var _i = 0, _a = Object.keys(object); _i < _a.length; _i++) {
        var key = _a[_i];
        newObject[key] = fn(object[key]);
    }
    return newObject;
}
//# sourceMappingURL=objectUtils.js.map