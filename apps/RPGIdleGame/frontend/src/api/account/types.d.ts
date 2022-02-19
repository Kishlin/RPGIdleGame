declare type RefreshAuthenticationApi = (
    onRefreshSuccess: (response: Response) => void,
    onRefreshFailure: (response: Response) => void,
) => void;
