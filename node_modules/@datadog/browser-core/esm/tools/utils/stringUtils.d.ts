/**
 * UUID v4
 * from https://gist.github.com/jed/982883
 */
export declare function generateUUID(placeholder?: string): string;
export declare function findCommaSeparatedValue(rawString: string, name: string): string | undefined;
export declare function findCommaSeparatedValues(rawString: string): Map<string, string>;
export declare function safeTruncate(candidate: string, length: number, suffix?: string): string;
