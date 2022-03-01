import { CardContent, Typography } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import CharacterFightingStats from '../Block/CharacterFightingStats';
import CharacterSkillsTable from '../Block/CharacterSkillsTable';

function CharacterCardContent({ character }: CharacterCardContentProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const characterProps = { ...character };

    return (
        <CardContent>
            <Typography>{t('components.character.meta.rank', { rank: character.rank })}</Typography>
            <Typography>{t('components.character.meta.skillPoints', { skillPoints: character.skill_points })}</Typography>
            <CharacterSkillsTable characterSkills={characterProps} />
            <CharacterFightingStats characterFightingStats={characterProps} />
        </CardContent>
    );
}

export default CharacterCardContent;
