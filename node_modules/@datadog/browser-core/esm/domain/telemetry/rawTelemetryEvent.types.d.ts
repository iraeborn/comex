import type { TelemetryEvent, TelemetryConfigurationEvent, TelemetryUsageEvent } from './telemetryEvent.types';
export declare const TelemetryType: {
    readonly log: "log";
    readonly configuration: "configuration";
    readonly usage: "usage";
};
export declare const enum StatusType {
    debug = "debug",
    error = "error"
}
export interface RuntimeEnvInfo {
    is_local_file: boolean;
    is_worker: boolean;
}
export type RawTelemetryEvent = TelemetryEvent['telemetry'];
export type RawTelemetryConfiguration = TelemetryConfigurationEvent['telemetry']['configuration'];
export type RawTelemetryUsage = TelemetryUsageEvent['telemetry']['usage'];
