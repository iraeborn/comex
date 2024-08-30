import type { RawError } from '@datadog/browser-core';
import type { LifeCycle } from './lifeCycle';
export declare function startReportError(lifeCycle: LifeCycle): (error: RawError) => void;
