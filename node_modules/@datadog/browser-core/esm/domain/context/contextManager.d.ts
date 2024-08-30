import type { Context } from '../../tools/serialisation/context';
import { Observable } from '../../tools/observable';
import type { CustomerDataTracker } from './customerDataTracker';
export type ContextManager = ReturnType<typeof createContextManager>;
export declare function createContextManager(customerDataTracker: CustomerDataTracker): {
    getContext: () => Context;
    setContext: (newContext: Context) => void;
    setContextProperty: (key: string, property: any) => void;
    removeContextProperty: (key: string) => void;
    clearContext: () => void;
    changeObservable: Observable<void>;
};
