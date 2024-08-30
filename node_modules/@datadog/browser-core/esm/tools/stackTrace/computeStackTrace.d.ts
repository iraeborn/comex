/**
 * Cross-browser stack trace computation.
 *
 * Reference implementation: https://github.com/csnover/TraceKit/blob/04530298073c3823de72deb0b97e7b38ca7bcb59/tracekit.js
 */
export interface StackFrame {
    url?: string;
    func?: string;
    /** The arguments passed to the function, if known. */
    args?: string[];
    line?: number;
    column?: number;
    /** An array of source code lines; the middle element corresponds to the correct line. */
    context?: string[];
}
export interface StackTrace {
    name?: string;
    message?: string;
    url?: string;
    stack: StackFrame[];
    incomplete?: boolean;
    partial?: boolean;
}
export declare function computeStackTrace(ex: unknown): StackTrace;
export declare function computeStackTraceFromOnErrorMessage(messageObj: unknown, url?: string, line?: number, column?: number): {
    name: string | undefined;
    message: string | undefined;
    stack: {
        url: string | undefined;
        column: number | undefined;
        line: number | undefined;
    }[];
};
