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

    const addOrReplaceCharacter = (character: Character) => {
        setCharacters({ ...characters, [character.id]: character });
    };

    const setCharactersFromArray = (list: Character[]) => {
        const characterList: CharacterList = {};

        list.forEach((character: Character): void => {
            characterList[character.id] = character;
        });

        setCharacters(characterList);
    };

    const context = useMemo<UserContextType>(
        () => ({
            isAuthenticated,
            characters,
            connect,
            disconnect,
            addOrReplaceCharacter,
            setCharactersFromArray,
        }),
        [characters, isAuthenticated],
    );

    return (
        <UserContext.Provider value={context}>
            { children }
        </UserContext.Provider>
    );
}
