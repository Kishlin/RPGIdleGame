declare type onDistributeSkillPointsFormSubmitFunction = (values: {
    health: number,
    attack: number,
    defense: number,
    magik: number,
}) => void;

declare type DistributeSkillPointsFormProps = {
    onFormSubmit: onDistributeSkillPointsFormSubmitFunction,
    character: Character,
    isLoading: boolean
}

declare type SkillDistributorProps = {
    label: string,
    skillLevel: number,
    amountToAdd: number,
    availablePoints: number,
    amountToAddSetter: (value: number) => void,
    costModifier: (value: number) => void,
    costComputer: (level: number) => number,
}
