import React, { useContext } from 'react';
import { Navigate, useParams } from 'react-router-dom';

import { UserContext } from '../../context/UserContext';

import CharacterInfoBox from '../../components/Character/CharacterInfoBox';
import NavigationButton from '../../components/Navigation/NavigationButton';

function DeleteCharacter(): JSX.Element {
    const { characters } = useContext<UserContextType>(UserContext);

    const { id } = useParams();

    const character = characters[id];

    if (undefined === character) {
        return (<Navigate to="/" />);
    }

    return (
        <>
            <p>Delete</p>
            <CharacterInfoBox character={character} />

            <NavigationButton text="pages.character.links.homepage" to="/" />
        </>
    );
}

export default DeleteCharacter;
