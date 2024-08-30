type Merged<TDestination, TSource> = TSource extends undefined ? TDestination : TDestination extends undefined ? TSource : TSource extends any[] ? TDestination extends any[] ? TDestination & TSource : TSource : TSource extends object ? TDestination extends object ? TDestination extends any[] ? TSource : TDestination & TSource : TSource : TSource;
/**
 * Iterate over source and affect its sub values into destination, recursively.
 * If the source and destination can't be merged, return source.
 */
export declare function mergeInto<D, S>(destination: D, source: S, circularReferenceChecker?: CircularReferenceChecker): Merged<D, S>;
/**
 * A simplistic implementation of a deep clone algorithm.
 * Caveats:
 * - It doesn't maintain prototype chains - don't use with instances of custom classes.
 * - It doesn't handle Map and Set
 */
export declare function deepClone<T>(value: T): T;
type Combined<A, B> = A extends null ? B : B extends null ? A : Merged<A, B>;
export declare function combine<A, B>(a: A, b: B): Combined<A, B>;
export declare function combine<A, B, C>(a: A, b: B, c: C): Combined<Combined<A, B>, C>;
export declare function combine<A, B, C, D>(a: A, b: B, c: C, d: D): Combined<Combined<Combined<A, B>, C>, D>;
export declare function combine<A, B, C, D, E>(a: A, b: B, c: C, d: D, e: E): Combined<Combined<Combined<Combined<A, B>, C>, D>, E>;
export declare function combine<A, B, C, D, E, F>(a: A, b: B, c: C, d: D, e: E, f: F): Combined<Combined<Combined<Combined<Combined<A, B>, C>, D>, E>, F>;
export declare function combine<A, B, C, D, E, F, G>(a: A, b: B, c: C, d: D, e: E, f: F, g: G): Combined<Combined<Combined<Combined<Combined<Combined<A, B>, C>, D>, E>, F>, G>;
export declare function combine<A, B, C, D, E, F, G, H>(a: A, b: B, c: C, d: D, e: E, f: F, g: G, h: H): Combined<Combined<Combined<Combined<Combined<Combined<Combined<A, B>, C>, D>, E>, F>, G>, H>;
interface CircularReferenceChecker {
    hasAlreadyBeenSeen(value: any): boolean;
}
export {};
