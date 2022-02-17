import React, { useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Button,
    Container,
    Stack,
    Typography,
} from '@mui/material';

import { LangContext } from '../context/LangContext';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';

function UnauthenticatedHome(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const navigate = useNavigate();

    return (
        <LayoutUnauthenticated>
            <Container maxWidth="sm" sx={{ mt: '5vh' }}>
                <Stack spacing={5}>
                    <Typography align="center" variant="h5">{ t('pages.home.anonymous.title') }</Typography>

                    <Button onClick={() => navigate('/signup')}>
                        { t('pages.home.anonymous.signup') }
                    </Button>

                    <Button onClick={() => navigate('/connect')} color="secondary">
                        { t('pages.home.anonymous.connect') }
                    </Button>
                </Stack>
            </Container>
        </LayoutUnauthenticated>
    );
}

export default UnauthenticatedHome;
