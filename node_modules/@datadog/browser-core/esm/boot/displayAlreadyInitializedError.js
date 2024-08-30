import { display } from '../tools/display';
export function displayAlreadyInitializedError(sdkName, initConfiguration) {
    if (!initConfiguration.silentMultipleInit) {
        display.error("".concat(sdkName, " is already initialized."));
    }
}
//# sourceMappingURL=displayAlreadyInitializedError.js.map