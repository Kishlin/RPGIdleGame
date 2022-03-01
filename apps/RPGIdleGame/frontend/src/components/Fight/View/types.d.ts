declare type FightParticipantViewProps = {
    participant: FightParticipant,
    result: FightResult
};

declare type FightResultHeaderProps = {
    result: FightResult
};

declare type FightTurnsViewProps = {
    winnerId: null|string,
    turns: FightTurn[],
};

declare type FightViewProps = {
    fight: Fight,
};

declare type FightViewHeaderProps = {
    initiatorResult: FightResult,
    opponentResult: FightResult,
    initiator: FightParticipant,
    opponent: FightParticipant,
};
