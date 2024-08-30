export type NetworkInterface = 'bluetooth' | 'cellular' | 'ethernet' | 'none' | 'wifi' | 'wimax' | 'other' | 'unknown';
export type EffectiveType = 'slow-2g' | '2g' | '3g' | '4g';
export interface NetworkInformation {
    type?: NetworkInterface;
    effectiveType?: EffectiveType;
}
export interface Connectivity {
    status: 'connected' | 'not_connected';
    interfaces?: NetworkInterface[];
    effective_type?: EffectiveType;
    [key: string]: unknown;
}
export declare function getConnectivity(): Connectivity;
