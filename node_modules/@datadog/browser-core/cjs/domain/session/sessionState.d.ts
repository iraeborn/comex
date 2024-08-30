export declare const EXPIRED = "1";
export interface SessionState {
    id?: string;
    created?: string;
    expire?: string;
    isExpired?: typeof EXPIRED;
    [key: string]: string | undefined;
}
export declare function getExpiredSessionState(): SessionState;
export declare function isSessionInNotStartedState(session: SessionState): boolean;
export declare function isSessionStarted(session: SessionState): boolean;
export declare function isSessionInExpiredState(session: SessionState): boolean;
export declare function expandSessionState(session: SessionState): void;
export declare function toSessionString(session: SessionState): string;
export declare function toSessionState(sessionString: string | undefined | null): SessionState;
