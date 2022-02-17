declare type onLogInFormSubmitFunction = (values: { email: string, password: string }) => void;

declare type LogInFormProps = {
    onFormSubmit: onLogInFormSubmitFunction,
    isLoading: boolean,
    error: null|string
}
