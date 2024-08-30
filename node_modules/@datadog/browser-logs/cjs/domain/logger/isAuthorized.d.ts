import type { Logger, HandlerType } from '../logger';
export declare function isAuthorized(status: StatusType, handlerType: HandlerType, logger: Logger): boolean;
export declare const StatusType: {
    readonly ok: "ok";
    readonly debug: "debug";
    readonly info: "info";
    readonly notice: "notice";
    readonly warn: "warn";
    readonly error: "error";
    readonly critical: "critical";
    readonly alert: "alert";
    readonly emerg: "emerg";
};
export declare const STATUS_PRIORITIES: {
    [key in StatusType]: number;
};
export type StatusType = (typeof StatusType)[keyof typeof StatusType];
