import type { Encoder } from '../../tools/encoder';
export type DeflateWorkerAction = {
    action: 'init';
} | {
    action: 'write';
    id: number;
    streamId: number;
    data: string;
} | {
    action: 'reset';
    streamId: number;
};
export type DeflateWorkerResponse = {
    type: 'initialized';
    version: string;
} | {
    type: 'wrote';
    id: number;
    streamId: number;
    result: Uint8Array;
    trailer: Uint8Array;
    additionalBytesCount: number;
} | {
    type: 'errored';
    streamId?: number;
    error: Error | string;
};
export interface DeflateWorker extends Worker {
    postMessage(message: DeflateWorkerAction): void;
}
export type DeflateEncoder = Encoder<Uint8Array> & {
    stop: () => void;
};
export declare const enum DeflateEncoderStreamId {
    REPLAY = 1,
    RUM = 2,
    RUM_REPLICA = 3
}
