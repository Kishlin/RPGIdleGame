declare type FightParticipantViewProps = {
    participant: FightParticipant,
    result: 'win'|'draw'|'loss'|string
};

declare type FightShortListProps = {
    fighterId: string;
}

declare type FightTurnsViewProps = {
    turns: FightTurn[],
}

declare type FightViewProps = {
    fight: Fight,
};
