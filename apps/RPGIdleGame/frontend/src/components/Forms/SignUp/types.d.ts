declare type onFormSubmitFunction = (values: { email: string, username: string, password: string }) => void;

declare type SignUpFormProps = {
    onFormSubmit: onFormSubmitFunction,
}

declare type FieldUsernameProps = {
    username: string,
    changeUsername: (username: string) => void,
}

declare type FieldEmailProps = {
    email: string,
    hasError: boolean,
    changeEmail: (email: string) => void,
}

declare type FieldPasswordProps = {
    password: string,
    changePassword: (password: string) => void,
    showPasswordAsText: boolean,
    togglePasswordVisibility: () => void,
}

declare type FieldPasswordCheckProps = {
    passwordCheck: string,
    hasError: boolean,
    changePasswordCheck: (passwordCheck: string) => void,
    showPasswordAsText: boolean,
    togglePasswordVisibility: () => void,
}

declare type ButtonSubmitProps = {
    disabled: boolean,
    onFormSubmit: () => void,
}
