declare type LangValue = 'en-US' | 'fr-FR';

declare type TranslateFunction = (key: string, parameters?: { [key: string]: any }) => string;

declare type LangContextType = {
    changeLang?: (lang: LangValue) => void
    t?: TranslateFunction,
    lang: LangValue,
};

declare type UserContextType = {
    isAuthenticated: boolean,
    characters: CharacterList,
    fights: FightList,
    setCharactersFromArray?: (list: Character[]) => void,
    addOrReplaceCharacter?: (character: Character) => void,
    setCharacters?: (list: CharacterList) => void,
    characterCreationIsAllowed?: () => boolean,
    storeFight?: (fight: Fight) => void,
    disconnect?: () => void,
    connect?: () => void,
};
