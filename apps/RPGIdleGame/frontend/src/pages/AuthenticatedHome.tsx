import React, { useContext, useEffect } from 'react';
import { Stack, Typography } from '@mui/material';

import { UserContext } from '../context/UserContext';
import { LangContext } from '../context/LangContext';

import LayoutAuthenticated from '../components/Layout/LayoutAuthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';
import CharactersList from '../components/Character/CharactersList';

import useAuthenticatedPage from '../hooks/useAuthenticatedPage';
import getAllCharactersUsingFetch from '../api/character/allCharacters';

function AuthenticatedHome(): JSX.Element {
    useAuthenticatedPage();

    const { characterCreationIsAllowed, setCharactersFromArray } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    useEffect(
        () => getAllCharactersUsingFetch(setCharactersFromArray),
        [],
    );

    const characterCreation = characterCreationIsAllowed()
        ? <NavigationButton text="pages.home.authenticated.buttons.createCharacter" to="/character/new" />
        : <Typography>{t('pages.home.authenticated.limitReached')}</Typography>;

    return (
        <LayoutAuthenticated>
            <Stack spacing={3}>
                <CharactersList />

                { characterCreation }
            </Stack>
        </LayoutAuthenticated>
    );
}

export default AuthenticatedHome;
