import React, { useContext, useState } from 'react';
import { Container, Grid } from '@mui/material';
import { useNavigate } from 'react-router-dom';

import { LangContext } from '../../context/LangContext';
import { UserContext } from '../../context/UserContext';

import CreateCharacterForm from '../../components/Forms/CreateCharacter/CreacteCharacterForm';
import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';
import NavigationButton from '../../components/Navigation/NavigationButton';
import createCharacterUsingFetch from '../../api/createCharacter';

function CreateCharacter(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);
    const { addCharacter } = useContext<UserContextType>(UserContext);

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>(null);

    const navigate = useNavigate();

    const onFormSubmit: onCreateCharacterFormSubmitFunction = (values) => {
        setIsLoading(true);

        createCharacterUsingFetch(
            values,
            (character: Character) => {
                addCharacter(character);
                setIsLoading(false);

                navigate('/');
            },
            () => {
                setIsLoading(false);
                setError(t('pages.character.formCreate.error'));
            },
        );
    };

    return (
        <LayoutAuthenticated>
            <Container maxWidth="sm">
                <Grid container spacing={3}>
                    <Grid item xs={12}>
                        <CreateCharacterForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />
                    </Grid>
                    <Grid item xs={12}>
                        <NavigationButton text="pages.character.links.homepage" to="/" variant="text" />
                    </Grid>
                </Grid>
            </Container>
        </LayoutAuthenticated>
    );
}

export default CreateCharacter;
