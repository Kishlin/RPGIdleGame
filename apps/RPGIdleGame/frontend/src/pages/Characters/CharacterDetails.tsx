import { Navigate, useParams } from 'react-router-dom';
import React, { useContext } from 'react';
import {
    Box,
    Container,
    Stack,
    Typography,
} from '@mui/material';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';
import FightShortList from '../../components/Fight/FightShortList';
import NavigationButton from '../../components/Navigation/NavigationButton';

function CharacterDetails(): JSX.Element {
    useAuthenticatedPage();

    const { characters } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const { id } = useParams();

    const character = characters[id];

    if (undefined === character) {
        return <Navigate to="/" />;
    }

    const score = {
        wins: character.wins_count,
        draws: character.draws_count,
        losses: character.losses_count,
    };

    return (
        <LayoutAuthenticated>
            <Container maxWidth="sm">
                <Stack spacing={3} sx={{ mb: 5 }}>
                    <Typography variant="h5">
                        {t('pages.character.view.title', { name: character.name })}
                    </Typography>

                    <Box textAlign="center">
                        <Typography>{t('pages.character.view.rank', { rank: character.rank })}</Typography>
                        <Typography>{t('pages.character.view.score', score)}</Typography>
                    </Box>

                    <FightShortList fighterId={character.id} />

                    <NavigationButton text="pages.character.view.home" to="/" variant="text" />
                </Stack>
            </Container>
        </LayoutAuthenticated>
    );
}

export default CharacterDetails;
