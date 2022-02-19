import React, { useContext } from 'react';
import { Container, Stack, Typography } from '@mui/material';

import LayoutAuthenticated from '../components/Layout/LayoutAuthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';
import CharactersList from '../components/Character/CharactersList';

import useAuthenticatedPage from '../hooks/useAuthenticatedPage';
import { UserContext } from '../context/UserContext';
import { LangContext } from '../context/LangContext';

function AuthenticatedHome(): JSX.Element {
    useAuthenticatedPage();

    const { characterCreationIsAllowed } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const characterCreation = characterCreationIsAllowed()
        ? <NavigationButton text="pages.home.authenticated.buttons.createCharacter" to="/character/new" />
        : <Typography>{t('pages.home.authenticated.limitReached')}</Typography>;

    return (
        <LayoutAuthenticated>
            <Container maxWidth="sm">
                <Stack spacing={3}>
                    <CharactersList />

                    { characterCreation }
                </Stack>
            </Container>
        </LayoutAuthenticated>
    );
}

export default AuthenticatedHome;
