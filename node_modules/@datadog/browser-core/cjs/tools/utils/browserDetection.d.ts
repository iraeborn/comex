export declare const enum Browser {
    IE = 0,
    CHROMIUM = 1,
    SAFARI = 2,
    OTHER = 3
}
export declare function isIE(): boolean;
export declare function isChromium(): boolean;
export declare function isSafari(): boolean;
export declare function detectBrowser(browserWindow?: Window): Browser;
