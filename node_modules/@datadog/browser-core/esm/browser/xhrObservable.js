import { instrumentMethod } from '../tools/instrumentMethod';
import { Observable } from '../tools/observable';
import { elapsed, clocksNow, timeStampNow } from '../tools/utils/timeUtils';
import { normalizeUrl } from '../tools/utils/urlPolyfill';
import { shallowClone } from '../tools/utils/objectUtils';
import { addEventListener } from './addEventListener';
var xhrObservable;
var xhrContexts = new WeakMap();
export function initXhrObservable(configuration) {
    if (!xhrObservable) {
        xhrObservable = createXhrObservable(configuration);
    }
    return xhrObservable;
}
function createXhrObservable(configuration) {
    return new Observable(function (observable) {
        var stopInstrumentingStart = instrumentMethod(XMLHttpRequest.prototype, 'open', openXhr).stop;
        var stopInstrumentingSend = instrumentMethod(XMLHttpRequest.prototype, 'send', function (call) {
            sendXhr(call, configuration, observable);
        }, { computeHandlingStack: true }).stop;
        var stopInstrumentingAbort = instrumentMethod(XMLHttpRequest.prototype, 'abort', abortXhr).stop;
        return function () {
            stopInstrumentingStart();
            stopInstrumentingSend();
            stopInstrumentingAbort();
        };
    });
}
function openXhr(_a) {
    var xhr = _a.target, _b = _a.parameters, method = _b[0], url = _b[1];
    xhrContexts.set(xhr, {
        state: 'open',
        method: String(method).toUpperCase(),
        url: normalizeUrl(String(url)),
    });
}
function sendXhr(_a, configuration, observable) {
    var xhr = _a.target, handlingStack = _a.handlingStack;
    var context = xhrContexts.get(xhr);
    if (!context) {
        return;
    }
    var startContext = context;
    startContext.state = 'start';
    startContext.startClocks = clocksNow();
    startContext.isAborted = false;
    startContext.xhr = xhr;
    startContext.handlingStack = handlingStack;
    var hasBeenReported = false;
    var stopInstrumentingOnReadyStateChange = instrumentMethod(xhr, 'onreadystatechange', function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            // Try to report the XHR as soon as possible, because the XHR may be mutated by the
            // application during a future event. For example, Angular is calling .abort() on
            // completed requests during an onreadystatechange event, so the status becomes '0'
            // before the request is collected.
            onEnd();
        }
    }).stop;
    var onEnd = function () {
        unsubscribeLoadEndListener();
        stopInstrumentingOnReadyStateChange();
        if (hasBeenReported) {
            return;
        }
        hasBeenReported = true;
        var completeContext = context;
        completeContext.state = 'complete';
        completeContext.duration = elapsed(startContext.startClocks.timeStamp, timeStampNow());
        completeContext.status = xhr.status;
        observable.notify(shallowClone(completeContext));
    };
    var unsubscribeLoadEndListener = addEventListener(configuration, xhr, 'loadend', onEnd).stop;
    observable.notify(startContext);
}
function abortXhr(_a) {
    var xhr = _a.target;
    var context = xhrContexts.get(xhr);
    if (context) {
        context.isAborted = true;
    }
}
//# sourceMappingURL=xhrObservable.js.map