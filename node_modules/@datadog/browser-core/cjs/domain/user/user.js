"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.sanitizeUser = sanitizeUser;
exports.checkUser = checkUser;
var display_1 = require("../../tools/display");
var typeUtils_1 = require("../../tools/utils/typeUtils");
var polyfills_1 = require("../../tools/utils/polyfills");
/**
 * Clone input data and ensure known user properties (id, name, email)
 * are strings, as defined here:
 * https://docs.datadoghq.com/logs/log_configuration/attributes_naming_convention/#user-related-attributes
 */
function sanitizeUser(newUser) {
    // We shallow clone only to prevent mutation of user data.
    var user = (0, polyfills_1.assign)({}, newUser);
    var keys = ['id', 'name', 'email'];
    keys.forEach(function (key) {
        if (key in user) {
            user[key] = String(user[key]);
        }
    });
    return user;
}
/**
 * Simple check to ensure user is valid
 */
function checkUser(newUser) {
    var isValid = (0, typeUtils_1.getType)(newUser) === 'object';
    if (!isValid) {
        display_1.display.error('Unsupported user:', newUser);
    }
    return isValid;
}
//# sourceMappingURL=user.js.map