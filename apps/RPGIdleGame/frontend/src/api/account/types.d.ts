declare type LogInApi = (
    params: { email: string, password: string },
    onLogInResponse?: (response: Response) => void,
) => void;

declare type LogOutApi = (
    onLoggingOutSuccess: () => void,
) => void;

declare type RefreshAuthenticationApi = (
    onRefreshSuccess: (response: Response) => void,
    onRefreshFailure: (response: Response) => void,
) => void;

declare type SignUpApi = (
    params: { email: string, username: string, password: string },
    onSignUpResponse?: (response: Response) => void,
) => void;
