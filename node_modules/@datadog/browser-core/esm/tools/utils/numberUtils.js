/**
 * Return true if the draw is successful
 * @param threshold between 0 and 100
 */
export function performDraw(threshold) {
    return threshold !== 0 && Math.random() * 100 <= threshold;
}
export function round(num, decimals) {
    return +num.toFixed(decimals);
}
export function isPercentage(value) {
    return isNumber(value) && value >= 0 && value <= 100;
}
export function isNumber(value) {
    return typeof value === 'number';
}
//# sourceMappingURL=numberUtils.js.map