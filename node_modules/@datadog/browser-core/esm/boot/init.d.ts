export interface PublicApi {
    /**
     * Version of the Logs browser SDK
     */
    version: string;
    /**
     * [For CDN async setup] Early RUM API calls must be wrapped in the `window.DD_RUM.onReady()` callback. This ensures the code only gets executed once the SDK is properly loaded.
     *
     * See [CDN async setup](https://docs.datadoghq.com/real_user_monitoring/browser/#cdn-async) for further information.
     */
    onReady: (callback: () => void) => void;
}
export declare function makePublicApi<T extends PublicApi>(stub: Omit<T, keyof PublicApi>): T;
export declare function defineGlobal<Global, Name extends keyof Global>(global: Global, name: Name, api: Global[Name]): void;
