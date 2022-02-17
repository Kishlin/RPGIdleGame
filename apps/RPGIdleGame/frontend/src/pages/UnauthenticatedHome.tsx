import React, { useContext } from 'react';
import { Container, Stack, Typography } from '@mui/material';

import { LangContext } from '../context/LangContext';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';

function UnauthenticatedHome(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <LayoutUnauthenticated>
            <Container maxWidth="sm">
                <Stack spacing={5}>
                    <Typography align="center" variant="h5">{ t('pages.home.anonymous.title') }</Typography>

                    <NavigationButton text="pages.home.anonymous.signup" to="/signup" variant="text" />

                    <NavigationButton text="pages.home.anonymous.login" to="/login" variant="text" color="secondary" />
                </Stack>
            </Container>
        </LayoutUnauthenticated>
    );
}

export default UnauthenticatedHome;
