import React, { useContext, useState } from 'react';
import { Navigate, useNavigate, useParams } from 'react-router-dom';
import { Container } from '@mui/material';

import { UserContext } from '../../context/UserContext';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';
import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';
import DistributeSkillPointsForm from '../../components/Forms/DistributeSkillPoints/DistributeSkillPointsForm';
import updateCharacterUsingFetch from '../../api/character/updateCharacter';

function DistributeSkillPoints(): JSX.Element {
    useAuthenticatedPage();

    const { characters, addOrReplaceCharacter } = useContext<UserContextType>(UserContext);

    const { id } = useParams();
    const navigate = useNavigate();

    const character = characters[id];

    if (undefined === character) {
        return <Navigate to="/" />;
    }

    const [isLoading, setIsLoading] = useState<boolean>(false);

    const onUpdateSuccess = (updatedCharacter: Character): void => {
        addOrReplaceCharacter(updatedCharacter);
        navigate('/');
    };

    const onFormSubmit: onDistributeSkillPointsFormSubmitFunction = (values) => {
        setIsLoading(true);
        updateCharacterUsingFetch(character.id, values, onUpdateSuccess);
    };

    return (
        <LayoutAuthenticated>
            <Container maxWidth="sm">
                <DistributeSkillPointsForm
                    onFormSubmit={onFormSubmit}
                    character={character}
                    isLoading={isLoading}
                />
            </Container>
        </LayoutAuthenticated>
    );
}

export default DistributeSkillPoints;
