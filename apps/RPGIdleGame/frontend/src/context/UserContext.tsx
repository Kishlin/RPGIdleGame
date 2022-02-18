import React, {
    createContext,
    ReactNode,
    useMemo,
    useState,
} from 'react';

export const UserContext = createContext<UserContextType>({ isAuthenticated: false, characters: {} });

export function UserProvider({ children }: { children: ReactNode }): JSX.Element {
    const [isAuthenticated, setIsAuthenticated] = useState<boolean>(false);
    const [characters, setCharacters] = useState<CharacterList>({});

    const connect = () => setIsAuthenticated(true);

    const disconnect = () => {
        setIsAuthenticated(false);
        setCharacters({});
    };

    const addCharacter = (character: Character) => {
        setCharacters({ ...characters, [character.id]: character });
    };

    const context = useMemo<UserContextType>(
        () => ({
            isAuthenticated,
            characters,
            connect,
            disconnect,
            setCharacters,
            addCharacter,
        }),
        [characters, isAuthenticated],
    );

    return (
        <UserContext.Provider value={context}>
            { children }
        </UserContext.Provider>
    );
}
