import React, { useContext, useState } from 'react';
import {
    Grid,
    Stack,
    Typography,
} from '@mui/material';

import NavigationButton from '../../Navigation/NavigationButton';
import ButtonSubmit from '../FormControl/ButtonSubmit';
import { LangContext } from '../../../context/LangContext';
import SkillDistributor from './SkillDistributor';

function distributeSkillPointsForm({
    onFormSubmit,
    isLoading,
    character,
}: DistributeSkillPointsFormProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [cost, setCost] = useState<number>(0);
    const [health, setHealth] = useState<number>(0);
    const [attack, setAttack] = useState<number>(0);
    const [defense, setDefense] = useState<number>(0);
    const [magik, setMagik] = useState<number>(0);

    const costsMoreThanPointsAvailable = cost > character.skill_points;

    const healthPointCostComputer = (): number => 1;
    const skillPointCostComputer = (value: number): number => Math.ceil((value) / 5);

    const costModifier = (value: number): void => setCost(cost + value);

    return (
        <Stack spacing={3}>
            <Typography variant="h5">
                {t('pages.character.formSkillPoints.title', { character_name: character.name })}
            </Typography>

            <Grid container>
                <Grid item xs={12} md={16}>
                    <Typography>
                        {t('pages.character.formSkillPoints.subtitleAvailable', { available: character.skill_points })}
                    </Typography>
                </Grid>
                <Grid item xs={12} md={16}>
                    <Typography color={costsMoreThanPointsAvailable ? 'error' : 'default'}>
                        {t('pages.character.formSkillPoints.subtitleCost', { cost })}
                    </Typography>
                </Grid>
            </Grid>

            <SkillDistributor
                label="entities.character.health"
                skillLevel={character.health}
                amountToAdd={health}
                amountToAddSetter={setHealth}
                costModifier={costModifier}
                costComputer={healthPointCostComputer}
            />

            <SkillDistributor
                label="entities.character.attack"
                skillLevel={character.attack}
                amountToAdd={attack}
                amountToAddSetter={setAttack}
                costModifier={costModifier}
                costComputer={skillPointCostComputer}
            />

            <SkillDistributor
                label="entities.character.defense"
                skillLevel={character.defense}
                amountToAdd={defense}
                amountToAddSetter={setDefense}
                costModifier={costModifier}
                costComputer={skillPointCostComputer}
            />

            <SkillDistributor
                label="entities.character.magik"
                skillLevel={character.magik}
                amountToAdd={magik}
                amountToAddSetter={setMagik}
                costModifier={costModifier}
                costComputer={skillPointCostComputer}
            />

            <Typography color="warning">{t('pages.character.formSkillPoints.warning')}</Typography>

            <ButtonSubmit
                text={t('pages.character.formSkillPoints.buttons.submit')}
                disabled={isLoading || costsMoreThanPointsAvailable || 0 === cost}
                onFormSubmit={() => onFormSubmit({
                    health,
                    attack,
                    defense,
                    magik,
                })}
            />

            <NavigationButton text="pages.character.formSkillPoints.buttons.home" to="/" color="warning" />
        </Stack>
    );
}

export default distributeSkillPointsForm;
