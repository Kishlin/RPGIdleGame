declare type FightParticipantViewProps = {
    participant: FightParticipant,
    result: FightResult
};

declare type FightResultHeaderProps = {
    result: FightResult
};

declare type FightTurnsViewProps = {
    turns: FightTurn[],
}

declare type FightViewProps = {
    fight: Fight,
};
