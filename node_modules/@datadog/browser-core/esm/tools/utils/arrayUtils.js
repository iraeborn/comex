import { arrayFrom } from './polyfills';
export function removeDuplicates(array) {
    var set = new Set();
    array.forEach(function (item) { return set.add(item); });
    return arrayFrom(set);
}
export function removeItem(array, item) {
    var index = array.indexOf(item);
    if (index >= 0) {
        array.splice(index, 1);
    }
}
//# sourceMappingURL=arrayUtils.js.map