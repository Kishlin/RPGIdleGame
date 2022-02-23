declare type AllFightsOfFighterApi = (
    fighterId: string,
    onSuccess: (fights: FightShort[]) => void
) => void;

declare type InitiateFightApi = (
    fighterId: string,
    onSuccess: (response: Response) => void
) => void;

declare type ViewFightApi = (
    id: string,
    onSuccess: (fight: Fight) => void
) => void;
