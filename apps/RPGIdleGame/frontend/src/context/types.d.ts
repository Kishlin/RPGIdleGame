declare type LangValue = 'EN' | 'FR';

declare type TranslateFunction = (key: string, parameters?: { [key: string]: any }) => string;

declare type LangContextType = {
    changeLang?: (lang: LangValue) => void
    t?: TranslateFunction,
    lang: LangValue,
};
