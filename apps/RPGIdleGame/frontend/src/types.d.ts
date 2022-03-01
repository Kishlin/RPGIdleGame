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

declare interface CharacterFightingStats {
    fights_count: number,
    wins_count: number,
    draws_count: number,
    losses_count: number,
}

declare interface CharacterSkills {
    health: number,
    attack: number,
    magik: number,
    defense: number,
}

declare interface Character extends CharacterSkills, CharacterFightingStats{
    [key: string]: string|number,
    id: string,
    owner: string,
    name: string,
    skill_points: number,
    rank: number,
    created_on: number,
    available_as_of: number
}

declare interface FightParticipant {
    [key: string]: string|number,
    character_name: string,
    character_id: string,
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
    winner_id: null|string,
    fight_date: number
}

declare interface FightShort {
    id: string,
    initiator_name: string,
    initiator_rank: number,
    opponent_name: string,
    opponent_rank: number,
    winner_id: string,
    fight_date: number
}

declare type FightResult = 'win'|'draw'|'loss'|string;

declare type CharacterList = { [key: string]: Character };

declare type FightList = { [key: string]: Fight };
