"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.includes = includes;
exports.arrayFrom = arrayFrom;
exports.find = find;
exports.findLast = findLast;
exports.forEach = forEach;
exports.objectValues = objectValues;
exports.objectEntries = objectEntries;
exports.startsWith = startsWith;
exports.endsWith = endsWith;
exports.assign = assign;
function includes(candidate, search) {
    return candidate.indexOf(search) !== -1;
}
function arrayFrom(arrayLike) {
    if (Array.from) {
        return Array.from(arrayLike);
    }
    var array = [];
    if (arrayLike instanceof Set) {
        arrayLike.forEach(function (item) { return array.push(item); });
    }
    else {
        for (var i = 0; i < arrayLike.length; i++) {
            array.push(arrayLike[i]);
        }
    }
    return array;
}
function find(array, predicate) {
    for (var i = 0; i < array.length; i += 1) {
        var item = array[i];
        if (predicate(item, i)) {
            return item;
        }
    }
    return undefined;
}
function findLast(array, predicate) {
    for (var i = array.length - 1; i >= 0; i -= 1) {
        var item = array[i];
        if (predicate(item, i, array)) {
            return item;
        }
    }
    return undefined;
}
function forEach(list, callback) {
    Array.prototype.forEach.call(list, callback);
}
function objectValues(object) {
    return Object.keys(object).map(function (key) { return object[key]; });
}
function objectEntries(object) {
    return Object.keys(object).map(function (key) { return [key, object[key]]; });
}
function startsWith(candidate, search) {
    return candidate.slice(0, search.length) === search;
}
function endsWith(candidate, search) {
    return candidate.slice(-search.length) === search;
}
function assign(target) {
    var toAssign = [];
    for (var _i = 1; _i < arguments.length; _i++) {
        toAssign[_i - 1] = arguments[_i];
    }
    toAssign.forEach(function (source) {
        for (var key in source) {
            if (Object.prototype.hasOwnProperty.call(source, key)) {
                target[key] = source[key];
            }
        }
    });
    return target;
}
//# sourceMappingURL=polyfills.js.map