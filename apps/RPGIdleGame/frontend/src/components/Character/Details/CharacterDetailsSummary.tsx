import { Box, Typography } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import CharacterFightingStats from '../Block/CharacterFightingStats';
import FirstAvailabilityLine from './FirstAvailabilityLine';

function CharacterDetailsSummary({ character }: CharacterDetailsBlockProps): JSX.Element {
    const { t, lang } = useContext<LangContextType>(LangContext);

    const creationDate = new Date(character.created_on * 1000).toLocaleString(lang);

    return (
        <Box textAlign="center">
            <Typography>{t('pages.character.view.rank', { rank: character.rank })}</Typography>
            <CharacterFightingStats characterFightingStats={{ ...character }} />
            <Typography>{t('pages.character.view.creation', { date: creationDate })}</Typography>
            <FirstAvailabilityLine character={character} />
        </Box>
    );
}

export default CharacterDetailsSummary;
