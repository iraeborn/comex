"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.removeDuplicates = removeDuplicates;
exports.removeItem = removeItem;
var polyfills_1 = require("./polyfills");
function removeDuplicates(array) {
    var set = new Set();
    array.forEach(function (item) { return set.add(item); });
    return (0, polyfills_1.arrayFrom)(set);
}
function removeItem(array, item) {
    var index = array.indexOf(item);
    if (index >= 0) {
        array.splice(index, 1);
    }
}
//# sourceMappingURL=arrayUtils.js.map