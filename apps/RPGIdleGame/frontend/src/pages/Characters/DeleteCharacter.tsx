import React, { useContext, useState } from 'react';
import { Navigate, useNavigate, useParams } from 'react-router-dom';
import { Button, Stack, Typography } from '@mui/material';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import CharacterCard from '../../components/Character/CharacterCard';
import NavigationButton from '../../components/Navigation/NavigationButton';
import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';

import deleteCharacterUsingFetch from '../../api/character/deleteCharacter';
import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';

function DeleteCharacter(): JSX.Element {
    useAuthenticatedPage();

    const { characters, setCharacters } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const { id } = useParams();
    const navigate = useNavigate();

    const character = characters[id];

    if (undefined === character) {
        return <Navigate to="/" />;
    }

    const [isLoading, setIsLoading] = useState<boolean>(false);

    const onDeletionSuccess = (): void => {
        delete characters[character.id];

        setCharacters(characters);

        navigate('/');
    };

    const onDeletionConfirmed = (): void => {
        setIsLoading(true);
        deleteCharacterUsingFetch(character.id, onDeletionSuccess);
    };

    return (
        <LayoutAuthenticated>
            <Stack spacing={3}>
                <Typography variant="h5" color="error">{t('pages.character.delete.title', { name: character.name })}</Typography>

                <CharacterCard character={character} withActions={false} />

                <Typography color="error">{t('pages.character.delete.validation1', { name: character.name })}</Typography>
                <Typography color="error">{t('pages.character.delete.validation2', { name: character.name })}</Typography>
                <Typography color="error">{t('pages.character.delete.validation3', { name: character.name })}</Typography>
                <Typography color="error">{t('pages.character.delete.validation4', { name: character.name })}</Typography>

                <NavigationButton text="pages.character.delete.buttons.home" to="/" color="primary" />

                <Button onClick={onDeletionConfirmed} disabled={isLoading} variant="contained" color="error">
                    {t('pages.character.delete.buttons.doDelete', { name: character.name })}
                </Button>
            </Stack>
        </LayoutAuthenticated>
    );
}

export default DeleteCharacter;
