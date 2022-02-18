import React from 'react';
import { Container } from '@mui/material';

import LayoutAuthenticated from '../components/Layout/LayoutAuthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';
import CharactersList from '../components/Character/CharactersList';

function AuthenticatedHome(): JSX.Element {
    return (
        <LayoutAuthenticated>
            <Container maxWidth="sm">
                <CharactersList />

                <NavigationButton text="pages.home.authenticated.buttons.createCharacter" to="/character/new" />
            </Container>
        </LayoutAuthenticated>
    );
}

export default AuthenticatedHome;
