import React, { useContext, useState } from 'react';
import { Navigate } from 'react-router-dom';
import { Container } from '@mui/material';

import { UserContext } from '../context/UserContext';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import SignUpForm from '../components/Forms/SignUp/SignUpForm';
import signUpUsingFetch from '../api/signup';

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
                    setError(null);
                }

                setIsAuthenticated(true);
                setIsLoading(false);
            },
        );
    };

    return (
        <LayoutUnauthenticated>
            <Container maxWidth="sm">
                <SignUpForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />
            </Container>
        </LayoutUnauthenticated>
    );
}

export default SignUp;
