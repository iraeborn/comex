export type MatchOption = string | RegExp | ((value: string) => boolean);
export declare function isMatchOption(item: unknown): item is MatchOption;
/**
 * Returns true if value can be matched by at least one of the provided MatchOptions.
 * When comparing strings, setting useStartsWith to true will compare the value with the start of
 * the option, instead of requiring an exact match.
 */
export declare function matchList(list: MatchOption[], value: string, useStartsWith?: boolean): boolean;
