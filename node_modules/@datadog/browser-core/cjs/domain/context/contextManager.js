"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.createContextManager = createContextManager;
var mergeInto_1 = require("../../tools/mergeInto");
var typeUtils_1 = require("../../tools/utils/typeUtils");
var sanitize_1 = require("../../tools/serialisation/sanitize");
var observable_1 = require("../../tools/observable");
function createContextManager(customerDataTracker) {
    var context = {};
    var changeObservable = new observable_1.Observable();
    var contextManager = {
        getContext: function () { return (0, mergeInto_1.deepClone)(context); },
        setContext: function (newContext) {
            if ((0, typeUtils_1.getType)(newContext) === 'object') {
                context = (0, sanitize_1.sanitize)(newContext);
                customerDataTracker.updateCustomerData(context);
            }
            else {
                contextManager.clearContext();
            }
            changeObservable.notify();
        },
        setContextProperty: function (key, property) {
            context[key] = (0, sanitize_1.sanitize)(property);
            customerDataTracker.updateCustomerData(context);
            changeObservable.notify();
        },
        removeContextProperty: function (key) {
            delete context[key];
            customerDataTracker.updateCustomerData(context);
            changeObservable.notify();
        },
        clearContext: function () {
            context = {};
            customerDataTracker.resetCustomerData();
            changeObservable.notify();
        },
        changeObservable: changeObservable,
    };
    return contextManager;
}
//# sourceMappingURL=contextManager.js.map