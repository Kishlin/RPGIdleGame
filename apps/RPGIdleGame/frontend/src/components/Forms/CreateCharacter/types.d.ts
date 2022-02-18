declare type onCreateCharacterFormSubmitFunction = (values: { name: string }) => void;

declare type CreateCharacterFormProps = {
    onFormSubmit: onCreateCharacterFormSubmitFunction,
    isLoading: boolean,
    error: null|string
}
