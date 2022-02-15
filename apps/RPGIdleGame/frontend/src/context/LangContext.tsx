import React, {
    createContext,
    ReactNode,
    useMemo,
    useState,
} from 'react';

import TranslationsEN from '../assets/translations/TranslationsEN';
import TranslationsFR from '../assets/translations/TranslationsFR';

export enum Lang {
    English = 'EN',
    French = 'FR',
}

export const LangContext = createContext<LangContextType>({ lang: Lang.English });

export function LangProvider({ children }: { children: ReactNode }): JSX.Element {
    const [translations, updateTranslations] = useState<Translations>(TranslationsEN);
    const [lang, updateLang] = useState<Lang>(Lang.English);

    const changeLang = (nextLang: Lang) => {
        updateLang(nextLang);

        switch (nextLang) {
        case Lang.French:
            updateTranslations(TranslationsFR);
            break;

        default:
            updateTranslations(TranslationsEN);
        }
    };

    const t: TranslateFunction = (key, parameters) => {
        let schema: any = translations;
        const keyList = key.split('.');
        const length = keyList.length - 1;

        for (let i = 0; i <= length; i += 1) {
            schema = schema[keyList[i]];
        } // schema is now the translation string.

        if ('object' !== typeof parameters || 0 === Object.keys(parameters.length).length) {
            return schema;
        }

        const parameterReplacer = (str: string, [k, v]: string[]): string => str.replace(new RegExp(`#${k}#`), v);

        return Object.entries(parameters).reduce(parameterReplacer, schema);
    };

    const context = useMemo<LangContextType>(() => ({ lang, changeLang, t }), [lang]);

    return (
        <LangContext.Provider value={context}>
            { children }
        </LangContext.Provider>
    );
}
