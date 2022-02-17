import React, { useContext, useState } from 'react';
import { Navigate } from 'react-router-dom';
import { Container, Grid } from '@mui/material';

import { UserContext } from '../context/UserContext';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';
import SignUpForm from '../components/Forms/SignUp/SignUpForm';

import signUpUsingFetch from '../api/signUp';

function SignUp(): JSX.Element {
    const { isAuthenticated, setIsAuthenticated } = useContext<UserContextType>(UserContext);

    if (isAuthenticated) {
        return <Navigate to="/" />;
    }

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>(null);

    const onFormSubmit: onFormSubmitFunction = ({ username, email, password }) => {
        setIsLoading(true);

        signUpUsingFetch(
            { username, email, password },
            (response: Response) => {
                if (false === response.ok) {
                    setError(409 === response.status ? 'conflict' : 'unknown');
                } else {
                    setIsAuthenticated(true);
                    setError(null);
                }

                setIsLoading(false);
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
                        <SignUpForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />
                    </Grid>
                    <Grid item xs={12}>
                        <NavigationButton text="pages.signup.links.login" to="/login" variant="text" />
                    </Grid>
                </Grid>
            </Container>
        </LayoutUnauthenticated>
    );
}

export default SignUp;
