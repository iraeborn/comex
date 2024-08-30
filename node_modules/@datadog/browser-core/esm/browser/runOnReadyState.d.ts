import type { Configuration } from '../domain/configuration';
export declare function runOnReadyState(configuration: Configuration, expectedReadyState: 'complete' | 'interactive', callback: () => void): void;
