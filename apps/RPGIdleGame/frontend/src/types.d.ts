declare module '*.svg' {
    const content: any;
    export default content;
}

declare module 'base-64' {
    const base64: {
        encode: (value: string) => string
    };
    export default base64;
}

declare interface Character {
    [key: string]: string|number,
    id: string,
    owner: string,
    name: string,
    skill_points: number,
    health: number,
    attack: number,
    magik: number,
    defense: number,
    rank: number,
    fights_count: number,
    wins_count: number,
    draws_count: number,
    losses_count: number,
}

declare interface FightParticipant {
    [key: string]: string|number,
    character_name: string,
    account_username: string,
    health: number,
    attack: number,
    defense: number,
    magik: number,
    rank: number
}

declare interface FightTurn {
    index: number,
    character_name: string,
    attacker_attack: number,
    attacker_dice_roll: number,
    attacker_magik: number,
    damage_dealt: number,
    defender_defense: number,
    defender_health: number,
}

declare interface Fight {
    id: string,
    initiator: FightParticipant,
    opponent: FightParticipant,
    turns: FightTurn[],
    winner_id: null|number
}

declare interface FightShort {
    id: string,
    initiator_name: string,
    initiator_rank: number,
    opponent_name: string,
    opponent_rank: number,
    winner_id: string
}

declare type CharacterList = { [key: string]: Character };

declare type FightList = { [key: string]: Fight };
