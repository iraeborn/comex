import { Observable } from '../tools/observable';
import type { Duration, ClocksState } from '../tools/utils/timeUtils';
import type { Configuration } from '../domain/configuration';
export interface XhrOpenContext {
    state: 'open';
    method: string;
    url: string;
}
export interface XhrStartContext extends Omit<XhrOpenContext, 'state'> {
    state: 'start';
    startClocks: ClocksState;
    isAborted: boolean;
    xhr: XMLHttpRequest;
    handlingStack?: string;
}
export interface XhrCompleteContext extends Omit<XhrStartContext, 'state'> {
    state: 'complete';
    duration: Duration;
    status: number;
}
export type XhrContext = XhrOpenContext | XhrStartContext | XhrCompleteContext;
export declare function initXhrObservable(configuration: Configuration): Observable<XhrContext>;
