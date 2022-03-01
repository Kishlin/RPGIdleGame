import React, { useContext } from 'react';
import { Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

function CharacterDetailsHeader({ characterName }: CharacterDetailsHeaderProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <Typography variant="h5">
            {t('pages.character.view.title', { name: characterName })}
        </Typography>
    );
}

export default CharacterDetailsHeader;
