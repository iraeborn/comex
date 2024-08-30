import type { Context, ContextValue } from './context';
/**
 * Ensures user-provided data is 'safe' for the SDK
 * - Deep clones data
 * - Removes cyclic references
 * - Transforms unserializable types to a string representation
 *
 * LIMITATIONS:
 * - Size is in characters, not byte count (may differ according to character encoding)
 * - Size does not take into account indentation that can be applied to JSON.stringify
 * - Non-numerical properties of Arrays are ignored. Same behavior as JSON.stringify
 *
 * @param source              User-provided data meant to be serialized using JSON.stringify
 * @param maxCharacterCount   Maximum number of characters allowed in serialized form
 */
export declare function sanitize(source: string, maxCharacterCount?: number): string | undefined;
export declare function sanitize(source: Context, maxCharacterCount?: number): Context;
export declare function sanitize(source: unknown, maxCharacterCount?: number): ContextValue;
