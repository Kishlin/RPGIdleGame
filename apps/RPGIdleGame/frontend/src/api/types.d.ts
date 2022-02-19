declare module 'base-64' {
    const base64: {
        encode: (value: string) => string
    };
    export default base64;
}

declare type SignUpApi = (
    params: { email: string, username: string, password: string },
    onSignUpResponse?: (response: Response) => void,
) => void;

declare type LogInApi = (
    params: { email: string, password: string },
    onLogInResponse?: (response: Response) => void,
) => void;

declare type LogOutApi = (
    onLoggingOutSuccess: () => void,
) => void;

declare type AllCharactersApi = (
    onCharactersListResponse: (characters: Array<Character>) => void,
) => void;

declare type CreateCharacterApi = (
    params: { name: string },
    onCreateCharacterResponse: (character: Character) => void,
    onCreationFailure: () => void,
) => void;
