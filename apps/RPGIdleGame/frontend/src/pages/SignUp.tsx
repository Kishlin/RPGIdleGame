import React from 'react';
import { Container, Fade } from '@mui/material';

import LayoutUnauthenticated from '../components/Layout/LayoutUnauthenticated';
import SignUpForm from '../components/Forms/SignUp/SignUpForm';

function SignUp(): JSX.Element {
    const onFormSubmit: onFormSubmitFunction = ({ username, email, password }) => {
        console.log(username, email, password);
    };

    return (
        <LayoutUnauthenticated>
            <Fade appear in easing={{ enter: 'ease-in' }}>
                <Container maxWidth="sm">
                    <SignUpForm onFormSubmit={onFormSubmit} />
                </Container>
            </Fade>
        </LayoutUnauthenticated>
    );
}

export default SignUp;
