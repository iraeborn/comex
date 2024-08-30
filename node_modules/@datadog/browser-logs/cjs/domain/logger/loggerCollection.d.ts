import type { TimeStamp } from '@datadog/browser-core';
import type { CommonContext } from '../../rawLogsEvent.types';
import type { LifeCycle } from '../lifeCycle';
import type { Logger, LogsMessage } from '../logger';
export declare function startLoggerCollection(lifeCycle: LifeCycle): {
    handleLog: (logsMessage: LogsMessage, logger: Logger, handlingStack?: string, savedCommonContext?: CommonContext, savedDate?: TimeStamp) => void;
};
