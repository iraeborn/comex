import type { Context } from '../../tools/serialisation/context';
import type { CustomerDataType } from './contextConstants';
export declare const CUSTOMER_DATA_BYTES_LIMIT: number;
export declare const CUSTOMER_COMPRESSED_DATA_BYTES_LIMIT: number;
export declare const BYTES_COMPUTATION_THROTTLING_DELAY = 200;
export type CustomerDataTracker = ReturnType<typeof createCustomerDataTracker>;
export type CustomerDataTrackerManager = ReturnType<typeof createCustomerDataTrackerManager>;
export declare const enum CustomerDataCompressionStatus {
    Unknown = 0,
    Enabled = 1,
    Disabled = 2
}
export declare function createCustomerDataTrackerManager(compressionStatus?: CustomerDataCompressionStatus): {
    /**
     * Creates a detached tracker. The manager will not store a reference to that tracker, and the
     * bytes count will be counted independently from other detached trackers.
     *
     * This is particularly useful when we don't know when the tracker will be unused, so we don't
     * leak memory (ex: when used in Logger instances).
     */
    createDetachedTracker: () => {
        updateCustomerData: (context: Context) => void;
        resetCustomerData: () => void;
        getBytesCount: () => number;
        stop: () => void;
    };
    /**
     * Creates a tracker if it doesn't exist, and returns it.
     */
    getOrCreateTracker: (type: CustomerDataType) => {
        updateCustomerData: (context: Context) => void;
        resetCustomerData: () => void;
        getBytesCount: () => number;
        stop: () => void;
    };
    setCompressionStatus: (newCompressionStatus: CustomerDataCompressionStatus) => void;
    getCompressionStatus: () => CustomerDataCompressionStatus;
    stop: () => void;
};
export declare function createCustomerDataTracker(checkCustomerDataLimit: () => void): {
    updateCustomerData: (context: Context) => void;
    resetCustomerData: () => void;
    getBytesCount: () => number;
    stop: () => void;
};
