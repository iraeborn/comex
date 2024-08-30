export declare function throttle<T extends (...args: any[]) => void>(fn: T, wait: number, options?: {
    leading?: boolean;
    trailing?: boolean;
}): {
    throttled: (...parameters: Parameters<T>) => void;
    cancel: () => void;
};
export declare function noop(): void;
