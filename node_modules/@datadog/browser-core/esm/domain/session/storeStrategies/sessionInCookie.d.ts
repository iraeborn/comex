import type { CookieOptions } from '../../../browser/cookie';
import type { InitConfiguration } from '../../configuration';
import type { SessionStoreStrategy, SessionStoreStrategyType } from './sessionStoreStrategy';
export declare function selectCookieStrategy(initConfiguration: InitConfiguration): SessionStoreStrategyType | undefined;
export declare function initCookieStrategy(cookieOptions: CookieOptions): SessionStoreStrategy;
export declare function buildCookieOptions(initConfiguration: InitConfiguration): CookieOptions;
