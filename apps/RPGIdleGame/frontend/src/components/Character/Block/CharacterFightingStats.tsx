import React, { useContext } from 'react';
import { Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

function CharacterFightingStats({ characterFightingStats }: CharacterFightingStatsProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <Typography>{t('components.character.meta.stats', { ...characterFightingStats })}</Typography>
    );
}

export default CharacterFightingStats;
