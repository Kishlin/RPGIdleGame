import React, { useState } from 'react';
import { Container, Fade } from '@mui/material';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import SignUpForm from '../components/Forms/SignUp/SignUpForm';
import signUpUsingFetch from '../api/signup';

function SignUp(): JSX.Element {
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

                setIsLoading(false);
            },
        );
    };

    return (
        <LayoutUnauthenticated>
            <Fade appear in easing={{ enter: 'ease-in' }}>
                <Container maxWidth="sm">
                    <SignUpForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />
                </Container>
            </Fade>
        </LayoutUnauthenticated>
    );
}

export default SignUp;
