declare type onLogInFormSubmitFunction = (values: { login: string, password: string }) => void;

declare type LogInFormProps = {
    onFormSubmit: onLogInFormSubmitFunction,
    isLoading: boolean,
    error: null|string
}
