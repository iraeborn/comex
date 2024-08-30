import type { StackTrace } from './computeStackTrace';
/**
 * Creates a stacktrace without SDK internal frames.
 * Constraints:
 * - Has to be called at the utmost position of the call stack.
 * - No monitored function should encapsulate it, that is why we need to use callMonitored inside it.
 */
export declare function createHandlingStack(): string;
export declare function toStackTraceString(stack: StackTrace): string;
export declare function formatErrorMessage(stack: StackTrace): string;
