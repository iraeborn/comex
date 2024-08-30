import { Observable } from '../../tools/observable';
import type { Configuration } from '../configuration';
import type { InterventionReport, DeprecationReport } from './browser.types';
export declare const RawReportType: {
    readonly intervention: "intervention";
    readonly deprecation: "deprecation";
    readonly cspViolation: "csp_violation";
};
export type RawReportType = (typeof RawReportType)[keyof typeof RawReportType];
export interface RawReport {
    type: RawReportType;
    subtype: string;
    message: string;
    originalReport: SecurityPolicyViolationEvent | DeprecationReport | InterventionReport;
    stack?: string;
}
export declare function initReportObservable(configuration: Configuration, apis: RawReportType[]): Observable<RawReport>;
