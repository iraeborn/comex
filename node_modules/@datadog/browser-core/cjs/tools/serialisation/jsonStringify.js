"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.jsonStringify = jsonStringify;
exports.detachToJsonMethod = detachToJsonMethod;
var functionUtils_1 = require("../utils/functionUtils");
/**
 * Custom implementation of JSON.stringify that ignores some toJSON methods. We need to do that
 * because some sites badly override toJSON on certain objects. Removing all toJSON methods from
 * nested values would be too costly, so we just detach them from the root value, and native classes
 * used to build JSON values (Array and Object).
 *
 * Note: this still assumes that JSON.stringify is correct.
 */
function jsonStringify(value, replacer, space) {
    if (typeof value !== 'object' || value === null) {
        return JSON.stringify(value);
    }
    // Note: The order matter here. We need to detach toJSON methods on parent classes before their
    // subclasses.
    var restoreObjectPrototypeToJson = detachToJsonMethod(Object.prototype);
    var restoreArrayPrototypeToJson = detachToJsonMethod(Array.prototype);
    var restoreValuePrototypeToJson = detachToJsonMethod(Object.getPrototypeOf(value));
    var restoreValueToJson = detachToJsonMethod(value);
    try {
        return JSON.stringify(value, replacer, space);
    }
    catch (_a) {
        return '<error: unable to serialize object>';
    }
    finally {
        restoreObjectPrototypeToJson();
        restoreArrayPrototypeToJson();
        restoreValuePrototypeToJson();
        restoreValueToJson();
    }
}
function detachToJsonMethod(value) {
    var object = value;
    var objectToJson = object.toJSON;
    if (objectToJson) {
        delete object.toJSON;
        return function () {
            object.toJSON = objectToJson;
        };
    }
    return functionUtils_1.noop;
}
//# sourceMappingURL=jsonStringify.js.map