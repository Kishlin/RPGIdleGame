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

    const context = useMemo<UserContextType>(
        () => ({
            isAuthenticated,
            characters,
            setIsAuthenticated,
            setCharacters,
        }),
        [characters, isAuthenticated],
    );

    return (
        <UserContext.Provider value={context}>
            { children }
        </UserContext.Provider>
    );
}
