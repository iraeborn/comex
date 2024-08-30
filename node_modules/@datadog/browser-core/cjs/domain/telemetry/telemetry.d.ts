import type { Context } from '../../tools/serialisation/context';
import type { Configuration } from '../configuration';
import { Observable } from '../../tools/observable';
import type { StackTrace } from '../../tools/stackTrace/computeStackTrace';
import type { TelemetryEvent } from './telemetryEvent.types';
import type { RawTelemetryConfiguration, RawTelemetryUsage } from './rawTelemetryEvent.types';
export declare const enum TelemetryService {
    LOGS = "browser-logs-sdk",
    RUM = "browser-rum-sdk"
}
export interface Telemetry {
    setContextProvider: (provider: () => Context) => void;
    observable: Observable<TelemetryEvent & Context>;
    enabled: boolean;
}
export declare function startTelemetry(telemetryService: TelemetryService, configuration: Configuration): Telemetry;
export declare function startFakeTelemetry(): (({
    [k: string]: unknown;
    device?: {
        architecture?: string;
        brand?: string;
        model?: string;
        [k: string]: unknown;
    };
    os?: {
        build?: string;
        name?: string;
        version?: string;
        [k: string]: unknown;
    };
} & {
    [k: string]: unknown;
    type?: "log";
    status: "error";
    message: string;
    error?: {
        stack?: string;
        kind?: string;
        [k: string]: unknown;
    };
}) | ({
    [k: string]: unknown;
    device?: {
        architecture?: string;
        brand?: string;
        model?: string;
        [k: string]: unknown;
    };
    os?: {
        build?: string;
        name?: string;
        version?: string;
        [k: string]: unknown;
    };
} & {
    [k: string]: unknown;
    type?: "log";
    status: "debug";
    message: string;
}) | ({
    [k: string]: unknown;
    device?: {
        architecture?: string;
        brand?: string;
        model?: string;
        [k: string]: unknown;
    };
    os?: {
        build?: string;
        name?: string;
        version?: string;
        [k: string]: unknown;
    };
} & {
    [k: string]: unknown;
    type: "configuration";
    configuration: {
        session_sample_rate?: number;
        telemetry_sample_rate?: number;
        telemetry_configuration_sample_rate?: number;
        telemetry_usage_sample_rate?: number;
        trace_sample_rate?: number;
        trace_context_injection?: "all" | "sampled";
        premium_sample_rate?: number;
        replay_sample_rate?: number;
        session_replay_sample_rate?: number;
        tracking_consent?: "granted" | "not-granted" | "pending";
        start_session_replay_recording_manually?: boolean;
        use_proxy?: boolean;
        use_before_send?: boolean;
        silent_multiple_init?: boolean;
        track_session_across_subdomains?: boolean;
        track_resources?: boolean;
        track_long_task?: boolean;
        use_cross_site_session_cookie?: boolean;
        use_partitioned_cross_site_session_cookie?: boolean;
        use_secure_session_cookie?: boolean;
        allow_fallback_to_local_storage?: boolean;
        store_contexts_across_pages?: boolean;
        allow_untrusted_events?: boolean;
        action_name_attribute?: string;
        use_allowed_tracing_origins?: boolean;
        use_allowed_tracing_urls?: boolean;
        selected_tracing_propagators?: ("datadog" | "b3" | "b3multi" | "tracecontext")[];
        default_privacy_level?: string;
        enable_privacy_for_action_name?: boolean;
        use_excluded_activity_urls?: boolean;
        use_worker_url?: boolean;
        compress_intake_requests?: boolean;
        track_frustrations?: boolean;
        track_views_manually?: boolean;
        track_interactions?: boolean;
        track_user_interactions?: boolean;
        forward_errors_to_logs?: boolean;
        forward_console_logs?: string[] | "all";
        forward_reports?: string[] | "all";
        use_local_encryption?: boolean;
        view_tracking_strategy?: "ActivityViewTrackingStrategy" | "FragmentViewTrackingStrategy" | "MixedViewTrackingStrategy" | "NavigationViewTrackingStrategy";
        track_background_events?: boolean;
        mobile_vitals_update_period?: number;
        track_errors?: boolean;
        track_network_requests?: boolean;
        use_tracing?: boolean;
        track_native_views?: boolean;
        track_native_errors?: boolean;
        track_native_long_tasks?: boolean;
        track_cross_platform_long_tasks?: boolean;
        use_first_party_hosts?: boolean;
        initialization_type?: string;
        track_flutter_performance?: boolean;
        batch_size?: number;
        batch_upload_frequency?: number;
        batch_processing_level?: number;
        background_tasks_enabled?: boolean;
        react_version?: string;
        react_native_version?: string;
        dart_version?: string;
        unity_version?: string;
        app_hang_threshold?: number;
        use_pci_intake?: boolean;
        tracer_api?: string;
        tracer_api_version?: string;
        send_logs_after_session_expiration?: boolean;
        plugins?: {
            name: string;
            [k: string]: unknown;
        }[];
        [k: string]: unknown;
    };
}) | ({
    [k: string]: unknown;
    device?: {
        architecture?: string;
        brand?: string;
        model?: string;
        [k: string]: unknown;
    };
    os?: {
        build?: string;
        name?: string;
        version?: string;
        [k: string]: unknown;
    };
} & {
    [k: string]: unknown;
    type: "usage";
    usage: import("./telemetryEvent.types").TelemetryCommonFeaturesUsage | import("./telemetryEvent.types").TelemetryBrowserFeaturesUsage;
}))[];
export declare function drainPreStartTelemetry(): void;
export declare function resetTelemetry(): void;
/**
 * Avoid mixing telemetry events from different data centers
 * but keep replicating staging events for reliability
 */
export declare function isTelemetryReplicationAllowed(configuration: Configuration): boolean;
export declare function addTelemetryDebug(message: string, context?: Context): void;
export declare function addTelemetryError(e: unknown, context?: Context): void;
export declare function addTelemetryConfiguration(configuration: RawTelemetryConfiguration): void;
export declare function addTelemetryUsage(usage: RawTelemetryUsage): void;
export declare function formatError(e: unknown): {
    error: {
        kind: string | undefined;
        stack: string;
    };
    message: string;
} | {
    error: {
        stack: string;
        kind?: undefined;
    };
    message: string;
};
export declare function scrubCustomerFrames(stackTrace: StackTrace): StackTrace;
