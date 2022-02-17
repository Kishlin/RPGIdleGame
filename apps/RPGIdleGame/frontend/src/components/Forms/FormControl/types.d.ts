declare type FieldUsernameProps = {
    label: string
    username: string,
    changeUsername: (username: string) => void,
}

declare type FieldEmailProps = {
    label: string,
    error: null|string,
    email: string,
    changeEmail: (email: string) => void,
}

declare type FieldPasswordProps = {
    label: string
    error: null|string,
    password: string,
    changePassword: (passwordCheck: string) => void,
    showPasswordAsText: boolean,
    togglePasswordVisibility: () => void,
}

declare type ButtonSubmitProps = {
    text: string
    disabled: boolean,
    onFormSubmit: () => void,
}
