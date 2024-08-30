export declare function shallowClone<T>(object: T): T & Record<string, never>;
export declare function objectHasValue<T extends {
    [key: string]: unknown;
}>(object: T, value: unknown): value is T[keyof T];
export declare function isEmptyObject(object: object): boolean;
export declare function mapValues<A, B>(object: {
    [key: string]: A;
}, fn: (arg: A) => B): {
    [key: string]: B;
};
