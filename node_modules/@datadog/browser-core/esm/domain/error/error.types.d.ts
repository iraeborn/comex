import type { ClocksState } from '../../tools/utils/timeUtils';
export interface ErrorWithCause extends Omit<Error, 'cause'> {
    cause?: unknown;
}
export type RawErrorCause = {
    message: string;
    source: string;
    type?: string;
    stack?: string;
};
export type Csp = {
    disposition: 'enforce' | 'report';
};
export interface RawError {
    startClocks: ClocksState;
    message: string;
    type?: string;
    stack?: string;
    source: ErrorSource;
    originalError?: unknown;
    handling?: ErrorHandling;
    handlingStack?: string;
    causes?: RawErrorCause[];
    fingerprint?: string;
    csp?: Csp;
}
export declare const ErrorSource: {
    readonly AGENT: "agent";
    readonly CONSOLE: "console";
    readonly CUSTOM: "custom";
    readonly LOGGER: "logger";
    readonly NETWORK: "network";
    readonly SOURCE: "source";
    readonly REPORT: "report";
};
export declare const enum NonErrorPrefix {
    UNCAUGHT = "Uncaught",
    PROVIDED = "Provided"
}
export declare const enum ErrorHandling {
    HANDLED = "handled",
    UNHANDLED = "unhandled"
}
export type ErrorSource = (typeof ErrorSource)[keyof typeof ErrorSource];
