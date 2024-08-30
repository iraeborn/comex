import { addTelemetryError } from '../domain/telemetry';
import { monitor } from '../tools/monitor';
import { addEventListener } from '../browser/addEventListener';
import { newRetryState, sendWithRetryStrategy } from './sendWithRetryStrategy';
export function createHttpRequest(configuration, endpointBuilder, bytesLimit, reportError) {
    var retryState = newRetryState();
    var sendStrategyForRetry = function (payload, onResponse) {
        return fetchKeepAliveStrategy(configuration, endpointBuilder, bytesLimit, payload, onResponse);
    };
    return {
        send: function (payload) {
            sendWithRetryStrategy(payload, retryState, sendStrategyForRetry, endpointBuilder.trackType, reportError);
        },
        /**
         * Since fetch keepalive behaves like regular fetch on Firefox,
         * keep using sendBeaconStrategy on exit
         */
        sendOnExit: function (payload) {
            sendBeaconStrategy(configuration, endpointBuilder, bytesLimit, payload);
        },
    };
}
function sendBeaconStrategy(configuration, endpointBuilder, bytesLimit, payload) {
    var canUseBeacon = !!navigator.sendBeacon && payload.bytesCount < bytesLimit;
    if (canUseBeacon) {
        try {
            var beaconUrl = endpointBuilder.build('beacon', payload);
            var isQueued = navigator.sendBeacon(beaconUrl, payload.data);
            if (isQueued) {
                return;
            }
        }
        catch (e) {
            reportBeaconError(e);
        }
    }
    var xhrUrl = endpointBuilder.build('xhr', payload);
    sendXHR(configuration, xhrUrl, payload.data);
}
var hasReportedBeaconError = false;
function reportBeaconError(e) {
    if (!hasReportedBeaconError) {
        hasReportedBeaconError = true;
        addTelemetryError(e);
    }
}
export function fetchKeepAliveStrategy(configuration, endpointBuilder, bytesLimit, payload, onResponse) {
    var canUseKeepAlive = isKeepAliveSupported() && payload.bytesCount < bytesLimit;
    if (canUseKeepAlive) {
        var fetchUrl = endpointBuilder.build('fetch', payload);
        fetch(fetchUrl, { method: 'POST', body: payload.data, keepalive: true, mode: 'cors' }).then(monitor(function (response) { return onResponse === null || onResponse === void 0 ? void 0 : onResponse({ status: response.status, type: response.type }); }), monitor(function () {
            var xhrUrl = endpointBuilder.build('xhr', payload);
            // failed to queue the request
            sendXHR(configuration, xhrUrl, payload.data, onResponse);
        }));
    }
    else {
        var xhrUrl = endpointBuilder.build('xhr', payload);
        sendXHR(configuration, xhrUrl, payload.data, onResponse);
    }
}
function isKeepAliveSupported() {
    // Request can throw, cf https://developer.mozilla.org/en-US/docs/Web/API/Request/Request#errors
    try {
        return window.Request && 'keepalive' in new Request('http://a');
    }
    catch (_a) {
        return false;
    }
}
export function sendXHR(configuration, url, data, onResponse) {
    var request = new XMLHttpRequest();
    request.open('POST', url, true);
    if (data instanceof Blob) {
        // When using a Blob instance, IE does not use its 'type' to define the 'Content-Type' header
        // automatically, so the intake request ends up being rejected with an HTTP status 415
        // Defining the header manually fixes this issue.
        request.setRequestHeader('Content-Type', data.type);
    }
    addEventListener(configuration, request, 'loadend', function () {
        onResponse === null || onResponse === void 0 ? void 0 : onResponse({ status: request.status });
    }, {
        // prevent multiple onResponse callbacks
        // if the xhr instance is reused by a third party
        once: true,
    });
    request.send(data);
}
//# sourceMappingURL=httpRequest.js.map