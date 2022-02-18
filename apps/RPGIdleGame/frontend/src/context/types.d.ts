declare type LangValue = 'EN' | 'FR';

declare type TranslateFunction = (key: string, parameters?: { [key: string]: any }) => string;

declare type LangContextType = {
    changeLang?: (lang: LangValue) => void
    t?: TranslateFunction,
    lang: LangValue,
};

declare type UserContextType = {
    isAuthenticated: boolean,
    characters: CharacterList,
    setIsAuthenticated?: (value: boolean) => void,
    setCharacters?: (value: CharacterList) => void,
    addCharacter?: (character: Character) => void,
};
