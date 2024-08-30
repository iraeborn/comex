"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.displayAlreadyInitializedError = displayAlreadyInitializedError;
var display_1 = require("../tools/display");
function displayAlreadyInitializedError(sdkName, initConfiguration) {
    if (!initConfiguration.silentMultipleInit) {
        display_1.display.error("".concat(sdkName, " is already initialized."));
    }
}
//# sourceMappingURL=displayAlreadyInitializedError.js.map