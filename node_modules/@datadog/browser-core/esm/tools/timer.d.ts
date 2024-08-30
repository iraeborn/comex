export type TimeoutId = ReturnType<typeof globalThis.setTimeout>;
export declare function setTimeout(callback: () => void, delay?: number): TimeoutId;
export declare function clearTimeout(timeoutId: TimeoutId | undefined): void;
export declare function setInterval(callback: () => void, delay?: number): TimeoutId;
export declare function clearInterval(timeoutId: TimeoutId | undefined): void;
