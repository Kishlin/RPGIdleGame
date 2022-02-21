declare module '*.svg' {
    const content: any;
    export default content;
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

declare type CharacterList = { [key: string]: Character };
