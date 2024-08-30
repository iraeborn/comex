import type { Payload } from '../../transport';
import type { InitConfiguration } from './configuration';
export type TrackType = 'logs' | 'rum' | 'replay';
export type ApiType = 'xhr' | 'fetch' | 'beacon' | 'manual';
export type EndpointBuilder = ReturnType<typeof createEndpointBuilder>;
export declare function createEndpointBuilder(initConfiguration: InitConfiguration, trackType: TrackType, configurationTags: string[]): {
    build(api: ApiType, payload: Payload): string;
    urlPrefix: string;
    trackType: TrackType;
};
