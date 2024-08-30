/**
 * Similar to `typeof`, but distinguish plain objects from `null` and arrays
 */
export declare function getType(value: unknown): "string" | "number" | "bigint" | "boolean" | "symbol" | "undefined" | "object" | "function" | "null" | "array";
