import type { EndpointBuilder, Configuration } from '../domain/configuration';
import type { Context } from '../tools/serialisation/context';
import type { RawError } from '../domain/error/error.types';
/**
 * Use POST request without content type to:
 * - avoid CORS preflight requests
 * - allow usage of sendBeacon
 *
 * multiple elements are sent separated by \n in order
 * to be parsed correctly without content type header
 */
export type HttpRequest = ReturnType<typeof createHttpRequest>;
export interface HttpResponse extends Context {
    status: number;
    type?: ResponseType;
}
export interface Payload {
    data: string | FormData | Blob;
    bytesCount: number;
    retry?: RetryInfo;
    encoding?: 'deflate';
}
export interface RetryInfo {
    count: number;
    lastFailureStatus: number;
}
export declare function createHttpRequest(configuration: Configuration, endpointBuilder: EndpointBuilder, bytesLimit: number, reportError: (error: RawError) => void): {
    send: (payload: Payload) => void;
    /**
     * Since fetch keepalive behaves like regular fetch on Firefox,
     * keep using sendBeaconStrategy on exit
     */
    sendOnExit: (payload: Payload) => void;
};
export declare function fetchKeepAliveStrategy(configuration: Configuration, endpointBuilder: EndpointBuilder, bytesLimit: number, payload: Payload, onResponse?: (r: HttpResponse) => void): void;
export declare function sendXHR(configuration: Configuration, url: string, data: Payload['data'], onResponse?: (r: HttpResponse) => void): void;
