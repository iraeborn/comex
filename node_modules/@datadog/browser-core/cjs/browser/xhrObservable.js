"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.initXhrObservable = initXhrObservable;
var instrumentMethod_1 = require("../tools/instrumentMethod");
var observable_1 = require("../tools/observable");
var timeUtils_1 = require("../tools/utils/timeUtils");
var urlPolyfill_1 = require("../tools/utils/urlPolyfill");
var objectUtils_1 = require("../tools/utils/objectUtils");
var addEventListener_1 = require("./addEventListener");
var xhrObservable;
var xhrContexts = new WeakMap();
function initXhrObservable(configuration) {
    if (!xhrObservable) {
        xhrObservable = createXhrObservable(configuration);
    }
    return xhrObservable;
}
function createXhrObservable(configuration) {
    return new observable_1.Observable(function (observable) {
        var stopInstrumentingStart = (0, instrumentMethod_1.instrumentMethod)(XMLHttpRequest.prototype, 'open', openXhr).stop;
        var stopInstrumentingSend = (0, instrumentMethod_1.instrumentMethod)(XMLHttpRequest.prototype, 'send', function (call) {
            sendXhr(call, configuration, observable);
        }, { computeHandlingStack: true }).stop;
        var stopInstrumentingAbort = (0, instrumentMethod_1.instrumentMethod)(XMLHttpRequest.prototype, 'abort', abortXhr).stop;
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
        url: (0, urlPolyfill_1.normalizeUrl)(String(url)),
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
    startContext.startClocks = (0, timeUtils_1.clocksNow)();
    startContext.isAborted = false;
    startContext.xhr = xhr;
    startContext.handlingStack = handlingStack;
    var hasBeenReported = false;
    var stopInstrumentingOnReadyStateChange = (0, instrumentMethod_1.instrumentMethod)(xhr, 'onreadystatechange', function () {
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
        completeContext.duration = (0, timeUtils_1.elapsed)(startContext.startClocks.timeStamp, (0, timeUtils_1.timeStampNow)());
        completeContext.status = xhr.status;
        observable.notify((0, objectUtils_1.shallowClone)(completeContext));
    };
    var unsubscribeLoadEndListener = (0, addEventListener_1.addEventListener)(configuration, xhr, 'loadend', onEnd).stop;
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