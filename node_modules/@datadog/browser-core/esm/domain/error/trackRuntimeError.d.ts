import type { Observable } from '../../tools/observable';
import type { StackTrace } from '../../tools/stackTrace/computeStackTrace';
import type { RawError } from './error.types';
export type UnhandledErrorCallback = (stackTrace: StackTrace, originalError?: any) => any;
export declare function trackRuntimeError(errorObservable: Observable<RawError>): {
    stop: () => void;
};
export declare function instrumentOnError(callback: UnhandledErrorCallback): {
    stop: typeof import("../..").noop;
};
export declare function instrumentUnhandledRejection(callback: UnhandledErrorCallback): {
    stop: typeof import("../..").noop;
};
