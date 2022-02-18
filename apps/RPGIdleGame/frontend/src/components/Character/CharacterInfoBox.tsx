import React from 'react';
import { Box } from '@mui/material';

import NavigationButton from '../Navigation/NavigationButton';

function CharacterInfoBox({ character }: CharacterInfoBoxProps): JSX.Element {
    return (
        <Box>
            <p>{`${character.name} - Skill Points: ${character.skill_points} - Rank: ${character.rank}`}</p>

            <NavigationButton text="components.character.links.skillPoints" to={`/character/${character.id}/skill-points`} />
            <NavigationButton text="components.character.links.fight" to={`/character/${character.id}/fight`} />

            <NavigationButton text="components.character.links.delete" to={`/character/${character.id}/delete`} color="error" />
        </Box>
);
}

export default CharacterInfoBox;
