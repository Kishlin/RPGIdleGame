import React, { useContext, useState } from 'react';
import { Navigate } from 'react-router-dom';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import LayoutUnauthenticatedForm from '../../components/Layout/LayoutUnauthenticatedForm';
import NavigationButton from '../../components/Navigation/NavigationButton';
import LogInForm from '../../components/Forms/Login/LogInForm';

import logInUsingFetch from '../../api/account/logIn';

import useAnonymousPage from '../../hooks/useAnonymousPage';

function LogIn(): JSX.Element {
    useAnonymousPage();

    const { isAuthenticated, connect, setCharactersFromArray } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [error, setError] = useState<string>(null);

    const onFormSubmit: onLogInFormSubmitFunction = ({ login, password }) => {
        setIsLoading(true);

        logInUsingFetch(
            { login, password },
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
        <LayoutUnauthenticatedForm
            form={<LogInForm onFormSubmit={onFormSubmit} isLoading={isLoading} error={error} />}
            navigationButton={<NavigationButton text="pages.login.links.signup" to="/signup" variant="text" />}
        />
    );
}

export default LogIn;
