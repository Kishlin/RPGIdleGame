import React, { useContext, useState } from 'react';
import { Navigate, useNavigate, useParams } from 'react-router-dom';
import {
    Box,
    Button,
    Stack,
    Typography,
} from '@mui/material';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';

import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';
import NavigationButton from '../../components/Navigation/NavigationButton';
import initiateFightUsingFetch from '../../api/fight/initiateFight';
import characterIsReadyToFight from '../../tools/characterIsReadyToFight';

const mapResponseStatusToError = (status: number): string => {
    if (404 === status) return 'noOpponent';
    if (400 === status) return 'resting';
    return 'unknown';
};

function FightWithCharacter(): JSX.Element {
    useAuthenticatedPage();

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<null|string>(null);

    const { characters, storeFight } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const { id } = useParams();

    const navigate = useNavigate();

    const character = characters[id];

    if (undefined === character) {
        return <Navigate to="/" />;
    }

    const onFightResponse = (response: Response) => {
        if (false === response.ok) {
            setError(mapResponseStatusToError(response.status));
            setIsLoading(false);
        } else {
            response.json().then((fight: Fight) => {
                storeFight(fight);

                navigate(`/fight/${fight.id}`);
            });
        }
    };

    const initiateFight = () => {
        setIsLoading(true);
        initiateFightUsingFetch(character.id, onFightResponse);
    };

    const errorMessage = null !== error
        ? <Typography color="error">{t(`pages.character.fight.errors.${error}`)}</Typography>
        : <noscript />;

    const fightButtonDisabled = isLoading
        || false === characterIsReadyToFight(character.available_as_of)
        || 'resting' === error;

    return (
        <LayoutAuthenticated>
            <Stack spacing={3}>
                <Typography variant="h5">{t('pages.character.fight.title', { name: character.name })}</Typography>

                <Typography align="justify">{t('pages.character.fight.explanations.main')}</Typography>

                <Box mt={0}>
                    <Typography align="justify">{t('pages.character.fight.explanations.example.title')}</Typography>
                    <Typography align="justify">{t('pages.character.fight.explanations.example.init')}</Typography>
                    <Typography align="justify">{t('pages.character.fight.explanations.example.roll')}</Typography>
                    <Typography align="justify">{t('pages.character.fight.explanations.example.bonus')}</Typography>
                    <Typography align="justify">{t('pages.character.fight.explanations.example.damage')}</Typography>
                    <Typography align="justify">{t('pages.character.fight.explanations.example.final')}</Typography>
                </Box>

                <Typography align="justify">{t('pages.character.fight.explanations.onResult')}</Typography>

                {errorMessage}

                <Button disabled={fightButtonDisabled} onClick={initiateFight} variant="contained">
                    {t('pages.character.fight.doFight')}
                </Button>

                <NavigationButton text="pages.character.links.homepage" to="/" variant="text" />
            </Stack>
        </LayoutAuthenticated>
    );
}

export default FightWithCharacter;
