import { setTimeout } from '../tools/timer';
import { clocksNow, ONE_MINUTE, ONE_SECOND } from '../tools/utils/timeUtils';
import { ONE_MEBI_BYTE, ONE_KIBI_BYTE } from '../tools/utils/byteUtils';
import { isServerError } from '../tools/utils/responseUtils';
import { ErrorSource } from '../domain/error/error.types';
export var MAX_ONGOING_BYTES_COUNT = 80 * ONE_KIBI_BYTE;
export var MAX_ONGOING_REQUESTS = 32;
export var MAX_QUEUE_BYTES_COUNT = 3 * ONE_MEBI_BYTE;
export var MAX_BACKOFF_TIME = ONE_MINUTE;
export var INITIAL_BACKOFF_TIME = ONE_SECOND;
export function sendWithRetryStrategy(payload, state, sendStrategy, trackType, reportError) {
    if (state.transportStatus === 0 /* TransportStatus.UP */ &&
        state.queuedPayloads.size() === 0 &&
        state.bandwidthMonitor.canHandle(payload)) {
        send(payload, state, sendStrategy, {
            onSuccess: function () { return retryQueuedPayloads(0 /* RetryReason.AFTER_SUCCESS */, state, sendStrategy, trackType, reportError); },
            onFailure: function () {
                state.queuedPayloads.enqueue(payload);
                scheduleRetry(state, sendStrategy, trackType, reportError);
            },
        });
    }
    else {
        state.queuedPayloads.enqueue(payload);
    }
}
function scheduleRetry(state, sendStrategy, trackType, reportError) {
    if (state.transportStatus !== 2 /* TransportStatus.DOWN */) {
        return;
    }
    setTimeout(function () {
        var payload = state.queuedPayloads.first();
        send(payload, state, sendStrategy, {
            onSuccess: function () {
                state.queuedPayloads.dequeue();
                state.currentBackoffTime = INITIAL_BACKOFF_TIME;
                retryQueuedPayloads(1 /* RetryReason.AFTER_RESUME */, state, sendStrategy, trackType, reportError);
            },
            onFailure: function () {
                state.currentBackoffTime = Math.min(MAX_BACKOFF_TIME, state.currentBackoffTime * 2);
                scheduleRetry(state, sendStrategy, trackType, reportError);
            },
        });
    }, state.currentBackoffTime);
}
function send(payload, state, sendStrategy, _a) {
    var onSuccess = _a.onSuccess, onFailure = _a.onFailure;
    state.bandwidthMonitor.add(payload);
    sendStrategy(payload, function (response) {
        state.bandwidthMonitor.remove(payload);
        if (!shouldRetryRequest(response)) {
            state.transportStatus = 0 /* TransportStatus.UP */;
            onSuccess();
        }
        else {
            // do not consider transport down if another ongoing request could succeed
            state.transportStatus =
                state.bandwidthMonitor.ongoingRequestCount > 0 ? 1 /* TransportStatus.FAILURE_DETECTED */ : 2 /* TransportStatus.DOWN */;
            payload.retry = {
                count: payload.retry ? payload.retry.count + 1 : 1,
                lastFailureStatus: response.status,
            };
            onFailure();
        }
    });
}
function retryQueuedPayloads(reason, state, sendStrategy, trackType, reportError) {
    if (reason === 0 /* RetryReason.AFTER_SUCCESS */ && state.queuedPayloads.isFull() && !state.queueFullReported) {
        reportError({
            message: "Reached max ".concat(trackType, " events size queued for upload: ").concat(MAX_QUEUE_BYTES_COUNT / ONE_MEBI_BYTE, "MiB"),
            source: ErrorSource.AGENT,
            startClocks: clocksNow(),
        });
        state.queueFullReported = true;
    }
    var previousQueue = state.queuedPayloads;
    state.queuedPayloads = newPayloadQueue();
    while (previousQueue.size() > 0) {
        sendWithRetryStrategy(previousQueue.dequeue(), state, sendStrategy, trackType, reportError);
    }
}
function shouldRetryRequest(response) {
    return (response.type !== 'opaque' &&
        ((response.status === 0 && !navigator.onLine) ||
            response.status === 408 ||
            response.status === 429 ||
            isServerError(response.status)));
}
export function newRetryState() {
    return {
        transportStatus: 0 /* TransportStatus.UP */,
        currentBackoffTime: INITIAL_BACKOFF_TIME,
        bandwidthMonitor: newBandwidthMonitor(),
        queuedPayloads: newPayloadQueue(),
        queueFullReported: false,
    };
}
function newPayloadQueue() {
    var queue = [];
    return {
        bytesCount: 0,
        enqueue: function (payload) {
            if (this.isFull()) {
                return;
            }
            queue.push(payload);
            this.bytesCount += payload.bytesCount;
        },
        first: function () {
            return queue[0];
        },
        dequeue: function () {
            var payload = queue.shift();
            if (payload) {
                this.bytesCount -= payload.bytesCount;
            }
            return payload;
        },
        size: function () {
            return queue.length;
        },
        isFull: function () {
            return this.bytesCount >= MAX_QUEUE_BYTES_COUNT;
        },
    };
}
function newBandwidthMonitor() {
    return {
        ongoingRequestCount: 0,
        ongoingByteCount: 0,
        canHandle: function (payload) {
            return (this.ongoingRequestCount === 0 ||
                (this.ongoingByteCount + payload.bytesCount <= MAX_ONGOING_BYTES_COUNT &&
                    this.ongoingRequestCount < MAX_ONGOING_REQUESTS));
        },
        add: function (payload) {
            this.ongoingRequestCount += 1;
            this.ongoingByteCount += payload.bytesCount;
        },
        remove: function (payload) {
            this.ongoingRequestCount -= 1;
            this.ongoingByteCount -= payload.bytesCount;
        },
    };
}
//# sourceMappingURL=sendWithRetryStrategy.js.map