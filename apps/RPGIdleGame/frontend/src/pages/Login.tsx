import React, { useContext, useState } from 'react';
import { Container, Grid } from '@mui/material';
import { Navigate } from 'react-router-dom';

import { UserContext } from '../context/UserContext';
import { LangContext } from '../context/LangContext';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';
import LogInForm from '../components/Forms/Login/LogInForm';

import logInUsingFetch from '../api/logIn';

import useAnonymousPage from '../hooks/useAnonymousPage';

function SignUp(): JSX.Element {
    useAnonymousPage();

    const { isAuthenticated, connect, setCharactersFromArray } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>(null);

    const onFormSubmit: onLogInFormSubmitFunction = ({ email, password }) => {
        setIsLoading(true);

        logInUsingFetch(
            { email, password },
            (response: Response) => {
                if (false === response.ok) {
                    setError(t(`pages.login.form.errors.${401 === response.status ? 'credentials' : 'unknown'}`));
                    setIsLoading(false);
                } else {
                    response.json().then((characters: Character[]) => {
                        setCharactersFromArray(characters);
                        connect();
                    });
                }
            },
        );
    };

    if (isAuthenticated) {
        return <Navigate to="/" />;
    }

    return (
        <LayoutUnauthenticated>
            <Container maxWidth="sm">
                <Grid container spacing={3}>
                    <Grid item xs={12}>
                        <LogInForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />
                    </Grid>
                    <Grid item xs={12}>
                        <NavigationButton text="pages.login.links.signup" to="/signup" variant="text" />
                    </Grid>
                </Grid>
            </Container>
        </LayoutUnauthenticated>
    );
}

export default SignUp;
