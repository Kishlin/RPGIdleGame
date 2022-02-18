import React, { useContext } from 'react';
import { Box } from '@mui/material';

import { UserContext } from '../../context/UserContext';

import CharacterInfoBox from './CharacterInfoBox';

function CharactersList(): JSX.Element {
    const { characters } = useContext<UserContextType>(UserContext);

    const charactersJSX = Object.keys(characters).map(
        (id: string) => (<CharacterInfoBox key={id} character={characters[id]} />),
    );

    return (
        <Box>
            {charactersJSX}
        </Box>
    );
}

export default CharactersList;
