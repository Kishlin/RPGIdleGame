import { Navigate, useParams } from 'react-router-dom';
import React, { useContext } from 'react';

import { UserContext } from '../../context/UserContext';

import CharacterDetailsBlock from '../../components/Character/Details/CharacterDetailsBlock';
import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';

function CharacterDetails(): JSX.Element {
    useAuthenticatedPage();

    const { characters } = useContext<UserContextType>(UserContext);

    const { id } = useParams();

    const character = characters[id];

    if (undefined === character) {
        return <Navigate to="/" />;
    }

    return (
        <LayoutAuthenticated>
            <CharacterDetailsBlock character={character} />
        </LayoutAuthenticated>
    );
}

export default CharacterDetails;
