import type { Configuration } from '../configuration';
import type { ContextManager } from './contextManager';
import type { CustomerDataType } from './contextConstants';
export declare function storeContextManager(configuration: Configuration, contextManager: ContextManager, productKey: string, customerDataType: CustomerDataType): void;
export declare function buildStorageKey(productKey: string, customerDataType: CustomerDataType): string;
export declare function removeStorageListeners(): void;
