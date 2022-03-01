import { Box, Stack, Typography } from '@mui/material';
import { Navigate, useParams } from 'react-router-dom';
import React, { useContext } from 'react';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';
import FightShortList from '../../components/Fight/FightShortList';
import NavigationButton from '../../components/Navigation/NavigationButton';

import characterIsReadyToFight from '../../tools/characterIsReadyToFight';
import timeFormat from '../../tools/dates';

function CharacterDetails(): JSX.Element {
    useAuthenticatedPage();

    const { characters } = useContext<UserContextType>(UserContext);
    const { t, lang } = useContext<LangContextType>(LangContext);

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

    const creationDate = new Date(character.created_on * 1000).toLocaleString(lang);

    const fightAvailabilityTypography = characterIsReadyToFight(character.available_as_of)
        ? (
            <NavigationButton
                variant="text"
                color="success"
                to={`/character/${character.id}/fight`}
                text="components.character.meta.available"
            />
        ) : (
            <Typography color="error">
                {t('components.character.meta.resting', { date: timeFormat(character.available_as_of, lang) })}
            </Typography>
        );

    return (
        <LayoutAuthenticated>
            <Stack spacing={3} sx={{ mb: 5 }}>
                <Typography variant="h5">
                    {t('pages.character.view.title', { name: character.name })}
                </Typography>

                <Box textAlign="center">
                    <Typography>{t('pages.character.view.rank', { rank: character.rank })}</Typography>
                    <Typography>{t('pages.character.view.score', score)}</Typography>
                    <Typography>{t('pages.character.view.creation', { date: creationDate })}</Typography>
                    {fightAvailabilityTypography}
                </Box>

                <NavigationButton text="pages.character.view.home" to="/" variant="text" />

                <FightShortList fighterId={character.id} />
            </Stack>
        </LayoutAuthenticated>
    );
}

export default CharacterDetails;
