import React, { useContext, useState } from 'react';
import { Container, Grid } from '@mui/material';

import { LangContext } from '../context/LangContext';

import CreateCharacterForm from '../components/Forms/CreateCharacter/CreacteCharacterForm';
import LayoutAuthenticated from '../components/Layout/LayoutAuthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';
import createCharacterUsingFetch from '../api/createCharacter';

function CreateCharacter(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>(null);

    const onFormSubmit: onCreateCharacterFormSubmitFunction = (values) => {
        setIsLoading(true);

        createCharacterUsingFetch(
            values,
            (character: Character) => {
                console.log(character);
                setIsLoading(false);
            },
            () => {
                setIsLoading(false);
                setError(t('pages.createCharacter.form.error'));
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
                        <NavigationButton text="pages.createCharacter.links.homepage" to="/" variant="text" />
                    </Grid>
                </Grid>
            </Container>
        </LayoutAuthenticated>
    );
}

export default CreateCharacter;
