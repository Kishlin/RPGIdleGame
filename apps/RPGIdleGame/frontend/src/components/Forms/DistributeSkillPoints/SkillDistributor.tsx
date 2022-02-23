import React, { useContext } from 'react';
import { ArrowRightAlt } from '@mui/icons-material';
import { Button, Grid, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

function SkillDistributor({
    label,
    skillLevel,
    amountToAdd,
    availablePoints,
    amountToAddSetter,
    costComputer,
    costModifier,
}: SkillDistributorProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const nextLevelCost = costComputer(skillLevel + amountToAdd + 1);

    const onPlusOneClick = () => {
        amountToAddSetter(amountToAdd + 1);
        costModifier(+nextLevelCost);
    };

    const onMinusOneClick = () => {
        amountToAddSetter(amountToAdd - 1);
        costModifier(-costComputer(skillLevel + amountToAdd));
    };

    return (
        <Grid container>
            <Grid item container direction="row" alignItems="center" justifyContent="space-between">
                <Typography sx={{ minWidth: '70px' }}>{t(label)}</Typography>
                <Grid item sx={{ width: '230px' }} container direction="row" alignItems="center" justifyContent="space-between">
                    <Button disabled={0 === amountToAdd} onClick={onMinusOneClick} variant="contained" color="warning">
                        {t('pages.character.formSkillPoints.buttons.minusOne')}
                    </Button>
                    <Typography>{skillLevel}</Typography>
                    <ArrowRightAlt />
                    <Typography>{skillLevel + amountToAdd}</Typography>
                    <Button disabled={nextLevelCost > availablePoints} onClick={onPlusOneClick} variant="contained" color="success">
                        {t('pages.character.formSkillPoints.buttons.plusOne')}
                    </Button>
                </Grid>
            </Grid>
            <Grid item>
                <Typography>
                    {t('pages.character.formSkillPoints.helperNextSkillLevel', { nextCost: nextLevelCost })}
                </Typography>
            </Grid>
        </Grid>
    );
}

export default SkillDistributor;
