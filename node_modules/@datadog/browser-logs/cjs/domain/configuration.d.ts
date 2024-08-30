import type { Configuration, InitConfiguration } from '@datadog/browser-core';
import { ConsoleApiName, RawReportType } from '@datadog/browser-core';
import type { LogsEvent } from '../logsEvent.types';
import type { LogsEventDomainContext } from '../domainContext.types';
export interface LogsInitConfiguration extends InitConfiguration {
    /**
     * Access to every logs collected by the Logs SDK before they are sent to Datadog.
     * It allows:
     * - Enrich your logs with additional context attributes
     * - Modify your logs to modify their content, or redact sensitive sequences (see the list of editable properties)
     * - Discard selected logs
     */
    beforeSend?: ((event: LogsEvent, context: LogsEventDomainContext) => boolean) | undefined;
    /**
     * Forward console.error logs, uncaught exceptions and network errors to Datadog.
     * @default true
     */
    forwardErrorsToLogs?: boolean | undefined;
    /**
     * Forward logs from console.* to Datadog. Use "all" to forward everything or an array of console API names to forward only a subset.
     */
    forwardConsoleLogs?: ConsoleApiName[] | 'all' | undefined;
    /**
     * Forward reports from the [Reporting API](https://developer.mozilla.org/en-US/docs/Web/API/Reporting_API) to Datadog. Use "all" to forward everything or an array of report types to forward only a subset.
     */
    forwardReports?: RawReportType[] | 'all' | undefined;
    /**
     * Use PCI-compliant intake. See [PCI DSS Compliance](https://docs.datadoghq.com/data_security/pci_compliance/?tab=logmanagement) for further information.
     * @default false
     */
    usePciIntake?: boolean;
    /**
     * Keep sending logs after the session expiration.
     * @default false
     */
    sendLogsAfterSessionExpiration?: boolean | undefined;
}
export type HybridInitConfiguration = Omit<LogsInitConfiguration, 'clientToken'>;
export interface LogsConfiguration extends Configuration {
    forwardErrorsToLogs: boolean;
    forwardConsoleLogs: ConsoleApiName[];
    forwardReports: RawReportType[];
    requestErrorResponseLengthLimit: number;
    sendLogsAfterSessionExpiration: boolean;
}
/**
 * arbitrary value, byte precision not needed
 */
export declare const DEFAULT_REQUEST_ERROR_RESPONSE_LENGTH_LIMIT: number;
export declare function validateAndBuildLogsConfiguration(initConfiguration: LogsInitConfiguration): LogsConfiguration | undefined;
export declare function validateAndBuildForwardOption<T>(option: readonly T[] | 'all' | undefined, allowedValues: T[], label: string): T[] | undefined;
export declare function serializeLogsConfiguration(configuration: LogsInitConfiguration): {
    forward_errors_to_logs: boolean | undefined;
    forward_console_logs: "all" | ConsoleApiName[] | undefined;
    forward_reports: "all" | RawReportType[] | undefined;
    use_pci_intake: boolean | undefined;
    send_logs_after_session_expiration: boolean | undefined;
} & {
    session_sample_rate: number | undefined;
    telemetry_sample_rate: number | undefined;
    telemetry_configuration_sample_rate: number | undefined;
    telemetry_usage_sample_rate: number | undefined;
    use_before_send: boolean;
    use_cross_site_session_cookie: boolean | undefined;
    use_partitioned_cross_site_session_cookie: boolean | undefined;
    use_secure_session_cookie: boolean | undefined;
    use_proxy: boolean;
    silent_multiple_init: boolean | undefined;
    track_session_across_subdomains: boolean | undefined;
    allow_fallback_to_local_storage: boolean;
    store_contexts_across_pages: boolean;
    allow_untrusted_events: boolean;
    tracking_consent: import("@datadog/browser-core").TrackingConsent | undefined;
};
