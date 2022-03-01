declare type CharacterCardActionFightProps = {
    id: string,
    availableAsOf: number,
};

declare type CharacterCardActionNavigation = {
    id: string,
};

declare type CharacterCardActionsProps = {
    id: string,
    availableAsOf: number,
    withActions: boolean,
};

declare type CharacterCardContentProps = {
    character: Character,
};

declare type CharacterCardHeaderProps = {
    id: string,
    name: string,
};

declare type CharacterCardProps = {
    character: Character,
    withActions?: boolean,
};

declare type NavigationButtonToCharacterDetailsProps = {
    id: string,
};
