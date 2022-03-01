import React, { useContext, useState } from 'react';
import { Navigate, useNavigate, useParams } from 'react-router-dom';
import { Button, Stack, Typography } from '@mui/material';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';

import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';
import NavigationButton from '../../components/Navigation/NavigationButton';
import initiateFightUsingFetch from '../../api/fight/initiateFight';
import characterIsReadyToFight from '../../tools/characterIsReadyToFight';

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
            setError(404 === response.status ? 'noOpponent' : 'unknown');
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

    const fightButtonDisabled = isLoading || false === characterIsReadyToFight(character.available_as_of);

    return (
        <LayoutAuthenticated>
            <Stack spacing={3}>
                <Typography variant="h5">{t('pages.character.fight.title', { name: character.name })}</Typography>

                <Typography align="justify">{t('pages.character.fight.explanations1')}</Typography>
                <Typography align="justify">{t('pages.character.fight.explanations2')}</Typography>

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
