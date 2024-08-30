"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.ONE_YEAR = exports.ONE_DAY = exports.ONE_HOUR = exports.ONE_MINUTE = exports.ONE_SECOND = void 0;
exports.relativeToClocks = relativeToClocks;
exports.timeStampToClocks = timeStampToClocks;
exports.currentDrift = currentDrift;
exports.toServerDuration = toServerDuration;
exports.dateNow = dateNow;
exports.timeStampNow = timeStampNow;
exports.relativeNow = relativeNow;
exports.clocksNow = clocksNow;
exports.clocksOrigin = clocksOrigin;
exports.elapsed = elapsed;
exports.addDuration = addDuration;
exports.getRelativeTime = getRelativeTime;
exports.getTimeStamp = getTimeStamp;
exports.looksLikeRelativeTime = looksLikeRelativeTime;
exports.resetNavigationStart = resetNavigationStart;
var numberUtils_1 = require("./numberUtils");
exports.ONE_SECOND = 1000;
exports.ONE_MINUTE = 60 * exports.ONE_SECOND;
exports.ONE_HOUR = 60 * exports.ONE_MINUTE;
exports.ONE_DAY = 24 * exports.ONE_HOUR;
exports.ONE_YEAR = 365 * exports.ONE_DAY;
function relativeToClocks(relative) {
    return { relative: relative, timeStamp: getCorrectedTimeStamp(relative) };
}
function timeStampToClocks(timeStamp) {
    return { relative: getRelativeTime(timeStamp), timeStamp: timeStamp };
}
function getCorrectedTimeStamp(relativeTime) {
    var correctedOrigin = (dateNow() - performance.now());
    // apply correction only for positive drift
    if (correctedOrigin > getNavigationStart()) {
        return Math.round(addDuration(correctedOrigin, relativeTime));
    }
    return getTimeStamp(relativeTime);
}
function currentDrift() {
    return Math.round(dateNow() - addDuration(getNavigationStart(), performance.now()));
}
function toServerDuration(duration) {
    if (!(0, numberUtils_1.isNumber)(duration)) {
        return duration;
    }
    return (0, numberUtils_1.round)(duration * 1e6, 0);
}
function dateNow() {
    // Do not use `Date.now` because sometimes websites are wrongly "polyfilling" it. For example, we
    // had some users using a very old version of `datejs`, which patched `Date.now` to return a Date
    // instance instead of a timestamp[1]. Those users are unlikely to fix this, so let's handle this
    // case ourselves.
    // [1]: https://github.com/datejs/Datejs/blob/97f5c7c58c5bc5accdab8aa7602b6ac56462d778/src/core-debug.js#L14-L16
    return new Date().getTime();
}
function timeStampNow() {
    return dateNow();
}
function relativeNow() {
    return performance.now();
}
function clocksNow() {
    return { relative: relativeNow(), timeStamp: timeStampNow() };
}
function clocksOrigin() {
    return { relative: 0, timeStamp: getNavigationStart() };
}
function elapsed(start, end) {
    return (end - start);
}
function addDuration(a, b) {
    return a + b;
}
/**
 * Get the time since the navigation was started.
 *
 * Note: this does not use `performance.timeOrigin` because it doesn't seem to reflect the actual
 * time on which the navigation has started: it may be much farther in the past, at least in Firefox 71.
 * Related issue in Firefox: https://bugzilla.mozilla.org/show_bug.cgi?id=1429926
 */
function getRelativeTime(timestamp) {
    return (timestamp - getNavigationStart());
}
function getTimeStamp(relativeTime) {
    return Math.round(addDuration(getNavigationStart(), relativeTime));
}
function looksLikeRelativeTime(time) {
    return time < exports.ONE_YEAR;
}
/**
 * Navigation start slightly change on some rare cases
 */
var navigationStart;
function getNavigationStart() {
    if (navigationStart === undefined) {
        navigationStart = performance.timing.navigationStart;
    }
    return navigationStart;
}
function resetNavigationStart() {
    navigationStart = undefined;
}
//# sourceMappingURL=timeUtils.js.map