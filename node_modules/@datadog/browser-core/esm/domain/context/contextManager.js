import { deepClone } from '../../tools/mergeInto';
import { getType } from '../../tools/utils/typeUtils';
import { sanitize } from '../../tools/serialisation/sanitize';
import { Observable } from '../../tools/observable';
export function createContextManager(customerDataTracker) {
    var context = {};
    var changeObservable = new Observable();
    var contextManager = {
        getContext: function () { return deepClone(context); },
        setContext: function (newContext) {
            if (getType(newContext) === 'object') {
                context = sanitize(newContext);
                customerDataTracker.updateCustomerData(context);
            }
            else {
                contextManager.clearContext();
            }
            changeObservable.notify();
        },
        setContextProperty: function (key, property) {
            context[key] = sanitize(property);
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