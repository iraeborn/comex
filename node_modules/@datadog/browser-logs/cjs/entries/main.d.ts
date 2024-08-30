import type { LogsPublicApi } from '../boot/logsPublicApi';
export { Logger, LogsMessage, HandlerType } from '../domain/logger';
export { StatusType } from '../domain/logger/isAuthorized';
export { LoggerConfiguration, LogsPublicApi as LogsGlobal } from '../boot/logsPublicApi';
export { LogsInitConfiguration } from '../domain/configuration';
export { LogsEvent } from '../logsEvent.types';
export { LogsEventDomainContext } from '../domainContext.types';
export declare const datadogLogs: LogsPublicApi;
