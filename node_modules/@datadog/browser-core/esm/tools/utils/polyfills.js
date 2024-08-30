export function includes(candidate, search) {
    return candidate.indexOf(search) !== -1;
}
export function arrayFrom(arrayLike) {
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
export function find(array, predicate) {
    for (var i = 0; i < array.length; i += 1) {
        var item = array[i];
        if (predicate(item, i)) {
            return item;
        }
    }
    return undefined;
}
export function findLast(array, predicate) {
    for (var i = array.length - 1; i >= 0; i -= 1) {
        var item = array[i];
        if (predicate(item, i, array)) {
            return item;
        }
    }
    return undefined;
}
export function forEach(list, callback) {
    Array.prototype.forEach.call(list, callback);
}
export function objectValues(object) {
    return Object.keys(object).map(function (key) { return object[key]; });
}
export function objectEntries(object) {
    return Object.keys(object).map(function (key) { return [key, object[key]]; });
}
export function startsWith(candidate, search) {
    return candidate.slice(0, search.length) === search;
}
export function endsWith(candidate, search) {
    return candidate.slice(-search.length) === search;
}
export function assign(target) {
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