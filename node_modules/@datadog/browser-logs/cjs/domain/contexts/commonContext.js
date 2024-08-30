"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.buildCommonContext = buildCommonContext;
function buildCommonContext(globalContextManager, userContextManager) {
    return {
        view: {
            referrer: document.referrer,
            url: window.location.href,
        },
        context: globalContextManager.getContext(),
        user: userContextManager.getContext(),
    };
}
//# sourceMappingURL=commonContext.js.map