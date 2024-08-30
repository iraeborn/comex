export declare function includes(candidate: string, search: string): boolean;
export declare function includes<T>(candidate: T[], search: T): boolean;
export declare function arrayFrom<T>(arrayLike: ArrayLike<T> | Set<T>): T[];
export declare function find<T, S extends T>(array: ArrayLike<T>, predicate: (item: T, index: number) => item is S): S | undefined;
export declare function find<T>(array: ArrayLike<T>, predicate: (item: T, index: number) => boolean): T | undefined;
export declare function findLast<T, S extends T>(array: T[], predicate: (item: T, index: number, array: T[]) => item is S): S | undefined;
export declare function forEach<List extends {
    [index: number]: any;
}>(list: List, callback: (value: List[number], index: number, parent: List) => void): void;
export declare function objectValues<T = unknown>(object: {
    [key: string]: T;
}): T[];
export declare function objectEntries<T = unknown>(object: {
    [key: string]: T;
}): Array<[string, T]>;
export declare function startsWith(candidate: string, search: string): boolean;
export declare function endsWith(candidate: string, search: string): boolean;
export declare function assign<T, U>(target: T, source: U): T & U;
export declare function assign<T, U, V>(target: T, source1: U, source2: V): T & U & V;
export declare function assign<T, U, V, W>(target: T, source1: U, source2: V, source3: W): T & U & V & W;
export declare function assign<T, U, V, W, X>(target: T, source1: U, source2: V, source3: W, source4: X): T & U & V & W & X;
