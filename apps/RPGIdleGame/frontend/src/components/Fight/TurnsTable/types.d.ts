declare type FightTurnsDetailedViewProps = {
    turns: FightTurn[],
};

declare type FightTurnsTableProps = {
    showTurnsWithoutDamage: boolean,
    turns: FightTurn[],
};

declare type FightTurnsTableRowProps = {
    turn: FightTurn,
};

declare type TurnsWithoutDamageToggleProps = {
    setValue: (newValue: boolean) => void,
    value: boolean,
};
