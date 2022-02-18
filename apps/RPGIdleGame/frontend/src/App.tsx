import React, { useContext, useEffect, useState } from 'react';
import {
    BrowserRouter as Router,
    Routes,
    Route,
    Navigate,
} from 'react-router-dom';

import { UserContext } from './context/UserContext';

import DistributeSkillPoints from './pages/Characters/DistributeSkillPoints';
import FightWithCharacter from './pages/Characters/FightWithCharacter';
import DeleteCharacter from './pages/Characters/DeleteCharacter';
import CreateCharacter from './pages/Characters/CreateCharacter';
import UnauthenticatedHome from './pages/UnauthenticatedHome';
import AuthenticatedHome from './pages/AuthenticatedHome';
import CheckHealth from './pages/CheckHealth';
import AppLoading from './pages/AppLoading';
import SignUp from './pages/SignUp';
import Login from './pages/Login';

import getAllCharactersUsingFetch from './api/allCharacters';

function App(): JSX.Element {
    const { isAuthenticated, setIsAuthenticated, setCharacters } = useContext(UserContext);
    const [isLoading, setIsLoading] = useState<boolean>(true);

    useEffect(
        () => getAllCharactersUsingFetch(
            (characters: Character[]) => {
                const characterList: CharacterList = {};

                characters.forEach((character: Character): void => {
                    characterList[character.id] = character;
                });

                setCharacters(characterList);
                setIsAuthenticated(true);
                setIsLoading(false);
            },
            () => {
                setIsAuthenticated(false);
                setIsLoading(false);
            },
        ),
        [],
    );

    if (isLoading) {
        return (
            <AppLoading />
        );
    }

    return (
        <Router>
            <Routes>
                <Route path="/character">
                    <Route path="new" element={isAuthenticated ? <CreateCharacter /> : <Navigate to="/" />} />
                    <Route path=":id">
                        <Route path="skill-points" element={isAuthenticated ? <DistributeSkillPoints /> : <Navigate to="/" />} />
                        <Route path="fight" element={isAuthenticated ? <FightWithCharacter /> : <Navigate to="/" />} />
                        <Route path="delete" element={isAuthenticated ? <DeleteCharacter /> : <Navigate to="/" />} />
                    </Route>
                </Route>

                <Route path="/" element={isAuthenticated ? <AuthenticatedHome /> : <UnauthenticatedHome />} />
                <Route path="/login" element={<Login />} />
                <Route path="/signup" element={<SignUp />} />

                <Route path="/monitoring/check-health" element={<CheckHealth />} />

                <Route path="*" element={<Navigate to="/" />} />
            </Routes>
        </Router>
    );
}

export default App;
