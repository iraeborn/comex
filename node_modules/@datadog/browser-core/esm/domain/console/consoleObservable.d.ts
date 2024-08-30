import { Observable } from '../../tools/observable';
import { ConsoleApiName } from '../../tools/display';
import type { RawErrorCause } from '../error/error.types';
export interface ConsoleLog {
    message: string;
    api: ConsoleApiName;
    stack?: string;
    handlingStack: string;
    fingerprint?: string;
    causes?: RawErrorCause[];
}
export declare function initConsoleObservable(apis: ConsoleApiName[]): Observable<ConsoleLog>;
export declare function resetConsoleObservable(): void;
