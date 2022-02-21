import React, { useContext } from 'react';
import { Navigate, useParams } from 'react-router-dom';

import { UserContext } from '../../context/UserContext';

import CharacterCard from '../../components/Character/CharacterCard';
import NavigationButton from '../../components/Navigation/NavigationButton';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';

function FightWithCharacter(): JSX.Element {
    useAuthenticatedPage();

    const { characters } = useContext<UserContextType>(UserContext);

    const { id } = useParams();

    const character = characters[id];

    if (undefined === character) {
        return (<Navigate to="/" />);
    }

    return (
        <>
            <p>Fight</p>

            <CharacterCard character={character} withActions={false} />

            <NavigationButton text="pages.character.links.homepage" to="/" />
        </>
    );
}

export default FightWithCharacter;
