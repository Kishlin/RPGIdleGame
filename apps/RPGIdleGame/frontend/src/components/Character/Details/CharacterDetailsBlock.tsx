import { Stack } from '@mui/material';
import React from 'react';

import NavigationButton from '../../Navigation/NavigationButton';
import CharacterDetailsSummary from './CharacterDetailsSummary';
import CharacterDetailsHeader from './CharacterDetailsHeader';
import FightShortList from '../../Fight/FightShortList';

function CharacterDetailsBlock({ character }: CharacterDetailsBlockProps): JSX.Element {
    return (
        <Stack spacing={3} sx={{ mb: 5 }}>
            <CharacterDetailsHeader characterName={character.name} />
            <CharacterDetailsSummary character={character} />

            <NavigationButton text="pages.character.view.home" to="/" variant="text" />

            <FightShortList fighterId={character.id} />
        </Stack>
    );
}

export default CharacterDetailsBlock;
