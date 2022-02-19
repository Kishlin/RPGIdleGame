declare type UpdateCharactersApi = (
    id: string,
    params: { health: number, attack: number, defense: number, magik: number },
    onSuccess: (character: Character) => void
) => void;
