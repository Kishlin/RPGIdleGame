import * as React from "react";

import {TranslationsEN} from "../assets/translations/TranslationsEN";
import {TranslationsFR} from "../assets/translations/TranslationsFR";

export enum Lang {
    English = 'EN',
    French = 'FR',
}

export const LangContext = React.createContext<LangContextType>({ lang: Lang.English });

export const LangProvider: (props: { children: React.ReactNode }) => JSX.Element = function(props) {
    const [ translations, updateTranslations ] = React.useState<Translations>(TranslationsEN);
    const [ lang, updateLang ] = React.useState<Lang>(Lang.English);

    const changeLang = (lang: Lang) => {
        updateLang(lang);

        switch(lang)  {
            case Lang.French:
                updateTranslations(TranslationsFR);
                break;

            default:
                updateTranslations(TranslationsEN);
        }
    }

    const t: TranslateFunction = (key, parameters) => {
        let schema: any = translations;
        const keyList = key.split('.');
        const length  = keyList.length - 1;

        for (let i = 0; i <= length; ++i) {
            schema = schema[keyList[i]];
        } // schema is now the translation string.

        if ('object' !== typeof parameters || 0 === Object.keys(parameters.length).length) {
            return schema;
        }

        const parameterReplacer = (str: string, [key, value]: string[]): string => str.replace(new RegExp(`#${key}#`), value);

        return Object.entries(parameters).reduce(parameterReplacer, schema);
    }

    return (
        <LangContext.Provider value={{ lang, changeLang, t }}>
            {props.children}
        </LangContext.Provider>
    );
}