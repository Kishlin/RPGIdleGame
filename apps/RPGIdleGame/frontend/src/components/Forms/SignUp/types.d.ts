declare type onFormSubmitFunction = (values: { email: string, username: string, password: string }) => void;

declare type SignUpFormProps = {
    onFormSubmit: onFormSubmitFunction,
    isLoading: boolean,
    error: null|string
}
