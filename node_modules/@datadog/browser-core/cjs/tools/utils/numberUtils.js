"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.performDraw = performDraw;
exports.round = round;
exports.isPercentage = isPercentage;
exports.isNumber = isNumber;
/**
 * Return true if the draw is successful
 * @param threshold between 0 and 100
 */
function performDraw(threshold) {
    return threshold !== 0 && Math.random() * 100 <= threshold;
}
function round(num, decimals) {
    return +num.toFixed(decimals);
}
function isPercentage(value) {
    return isNumber(value) && value >= 0 && value <= 100;
}
function isNumber(value) {
    return typeof value === 'number';
}
//# sourceMappingURL=numberUtils.js.map