import React, { useContext } from 'react';
import { Grid } from '@mui/material';

import { UserContext } from '../../context/UserContext';

import CharacterCard from './CharacterCard';

function CharactersList(): JSX.Element {
    const { characters } = useContext<UserContextType>(UserContext);

    const charactersJSX = Object.keys(characters).map(
        (id: string) => (
            <Grid key={id} item xs={12} sm={6} md={4}>
                <CharacterCard character={characters[id]} />
            </Grid>
        ),
    );

    return (
        <Grid container spacing={3}>
            {charactersJSX}
        </Grid>
    );
}

export default CharactersList;
