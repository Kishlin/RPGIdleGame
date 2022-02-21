declare type AllCharactersApi = (
    onCharactersListResponse: (characters: Array<Character>) => void,
) => void;

declare type CreateCharacterApi = (
    params: { name: string },
    onCreateCharacterResponse: (character: Character) => void,
    onCreationFailure: () => void,
) => void;

declare type DeleteCharactersApi = (
    id: string,
    onSuccess: () => void
) => void;

declare type UpdateCharactersApi = (
    id: string,
    params: { health: number, attack: number, defense: number, magik: number },
    onSuccess: (character: Character) => void
) => void;
