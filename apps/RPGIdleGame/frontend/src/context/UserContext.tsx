import React, {
    createContext,
    ReactNode,
    useMemo,
    useState,
} from 'react';

export const UserContext = createContext<UserContextType>({ isAuthenticated: false, characters: {}, fights: {} });

export function UserProvider({ children }: { children: ReactNode }): JSX.Element {
    const [isAuthenticated, setIsAuthenticated] = useState<boolean>(false);
    const [characters, setCharacters] = useState<CharacterList>({});
    const [fights, setFights] = useState<FightList>({});

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

    const characterCreationIsAllowed = () => 10 > Object.keys(characters).length;

    const storeFight = (fight: Fight) => {
        setFights({ ...fights, [fight.id]: fight });
    };

    const context = useMemo<UserContextType>(
        () => ({
            isAuthenticated,
            characters,
            fights,
            connect,
            disconnect,
            addOrReplaceCharacter,
            setCharactersFromArray,
            setCharacters,
            characterCreationIsAllowed,
            storeFight,
        }),
        [characters, fights, isAuthenticated],
    );

    return (
        <UserContext.Provider value={context}>
            { children }
        </UserContext.Provider>
    );
}
