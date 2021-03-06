import React, { useContext } from 'react';
import { Stack, Typography } from '@mui/material';

import { LangContext } from '../context/LangContext';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';

import useAnonymousPage from '../hooks/useAnonymousPage';

function UnauthenticatedHome(): JSX.Element {
    useAnonymousPage();

    const { t } = useContext<LangContextType>(LangContext);

    return (
        <LayoutUnauthenticated>
            <Stack spacing={5}>
                <Typography align="center" variant="h5">{ t('pages.home.anonymous.title') }</Typography>

                <NavigationButton text="pages.home.anonymous.signup" to="/signup" variant="text" />

                <NavigationButton text="pages.home.anonymous.login" to="/login" variant="text" color="secondary" />
            </Stack>
        </LayoutUnauthenticated>
    );
}

export default UnauthenticatedHome;
