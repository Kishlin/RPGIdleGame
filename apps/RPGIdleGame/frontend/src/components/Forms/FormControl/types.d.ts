declare type FieldTextProps = {
    label: string,
    value: string,
    changeValue: (value: string) => void,
    error?: null|string,
    type?: 'text'|'email',
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
    // @ts-ignore
    endIcon: OverridableComponent<SvgIconTypeMap>
}
