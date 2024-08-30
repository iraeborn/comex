import type { ClocksState } from '../../tools/utils/timeUtils';
import type { StackTrace } from '../../tools/stackTrace/computeStackTrace';
import type { ErrorSource, ErrorHandling, RawError, RawErrorCause, ErrorWithCause, NonErrorPrefix } from './error.types';
export declare const NO_ERROR_STACK_PRESENT_MESSAGE = "No stack, consider using an instance of Error";
type RawErrorParams = {
    stackTrace?: StackTrace;
    originalError: unknown;
    handlingStack?: string;
    startClocks: ClocksState;
    nonErrorPrefix: NonErrorPrefix;
    source: ErrorSource;
    handling: ErrorHandling;
};
export declare function computeRawError({ stackTrace, originalError, handlingStack, startClocks, nonErrorPrefix, source, handling, }: RawErrorParams): RawError;
export declare function tryToGetFingerprint(originalError: unknown): string | undefined;
export declare function getFileFromStackTraceString(stack: string): string | undefined;
export declare function flattenErrorCauses(error: ErrorWithCause, parentSource: ErrorSource): RawErrorCause[] | undefined;
export {};
