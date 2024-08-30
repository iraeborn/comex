/**
 * Custom implementation of JSON.stringify that ignores some toJSON methods. We need to do that
 * because some sites badly override toJSON on certain objects. Removing all toJSON methods from
 * nested values would be too costly, so we just detach them from the root value, and native classes
 * used to build JSON values (Array and Object).
 *
 * Note: this still assumes that JSON.stringify is correct.
 */
export declare function jsonStringify(value: unknown, replacer?: Array<string | number>, space?: string | number): string | undefined;
export interface ObjectWithToJsonMethod {
    toJSON?: () => unknown;
}
export declare function detachToJsonMethod(value: object): () => void;
