import React, { useContext } from 'react';
import { Box } from '@mui/material';

import { Lang, LangContext } from '../../context/LangContext';

import EnglishFlag from './united-kingdom.svg';
import FrenchFlag from './france.svg';
import './ChangeLangButton.css';

function ChangeLangButton(): JSX.Element {
    const { lang, changeLang } = useContext(LangContext);

    return (
        <Box display="flex" justifyContent="flex-end" className="lang-button-box">
            <button type="button" data-current={Lang.English === lang} onClick={() => changeLang(Lang.English)}>
                <img src={EnglishFlag} alt="English" />
            </button>
            <button type="button" data-current={Lang.French === lang} onClick={() => changeLang(Lang.French)}>
                <img src={FrenchFlag} alt="Français" />
            </button>
        </Box>
    );
}

export default ChangeLangButton;
