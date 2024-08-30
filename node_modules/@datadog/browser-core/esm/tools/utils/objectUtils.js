import { assign } from './polyfills';
export function shallowClone(object) {
    return assign({}, object);
}
export function objectHasValue(object, value) {
    return Object.keys(object).some(function (key) { return object[key] === value; });
}
export function isEmptyObject(object) {
    return Object.keys(object).length === 0;
}
export function mapValues(object, fn) {
    var newObject = {};
    for (var _i = 0, _a = Object.keys(object); _i < _a.length; _i++) {
        var key = _a[_i];
        newObject[key] = fn(object[key]);
    }
    return newObject;
}
//# sourceMappingURL=objectUtils.js.map