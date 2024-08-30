import type { RawError } from '../error/error.types';
export type EventRateLimiter = ReturnType<typeof createEventRateLimiter>;
export declare function createEventRateLimiter(eventType: string, limit: number, onLimitReached: (limitError: RawError) => void): {
    isLimitReached(): boolean;
};
