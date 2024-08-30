import type { DefaultPrivacyLevel } from '../domain/configuration';
export interface BrowserWindowWithEventBridge extends Window {
    DatadogEventBridge?: DatadogEventBridge;
}
export interface DatadogEventBridge {
    getCapabilities?(): string;
    getPrivacyLevel?(): DefaultPrivacyLevel;
    getAllowedWebViewHosts(): string;
    send(msg: string): void;
}
export declare const enum BridgeCapability {
    RECORDS = "records"
}
export declare function getEventBridge<T, E>(): {
    getCapabilities(): BridgeCapability[];
    getPrivacyLevel(): DefaultPrivacyLevel | undefined;
    getAllowedWebViewHosts(): string[];
    send(eventType: T, event: E, viewId?: string): void;
} | undefined;
export declare function bridgeSupports(capability: BridgeCapability): boolean;
export declare function canUseEventBridge(currentHost?: string): boolean;
