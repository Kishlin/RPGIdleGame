import React, { useContext, useState } from 'react';
import { Navigate } from 'react-router-dom';

import { UserContext } from '../../context/UserContext';

import LayoutUnauthenticatedForm from '../../components/Layout/LayoutUnauthenticatedForm';
import NavigationButton from '../../components/Navigation/NavigationButton';
import SignUpForm from '../../components/Forms/SignUp/SignUpForm';

import signUpUsingFetch from '../../api/account/signUp';
import useAnonymousPage from '../../hooks/useAnonymousPage';

function SignUp(): JSX.Element {
    useAnonymousPage();

    const { isAuthenticated, connect } = useContext<UserContextType>(UserContext);

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>(null);

    if (isAuthenticated) {
        return <Navigate to="/" />;
    }

    const onFormSubmit: onFormSubmitFunction = ({ username, email, password }) => {
        setIsLoading(true);

        signUpUsingFetch(
            { username, email, password },
            (response: Response) => {
                if (false === response.ok) {
                    if (409 === response.status) {
                        response.json().then((body: { field: string }) => setError(`conflict.${body.field}`));
                    } else {
                        setError('unknown');
                    }

                    setIsLoading(false);
                } else {
                    connect();
                }
            },
        );
    };

    return (
        <LayoutUnauthenticatedForm
            form={<SignUpForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />}
            navigationButton={<NavigationButton text="pages.signup.links.login" to="/login" variant="text" />}
        />
    );
}

export default SignUp;
