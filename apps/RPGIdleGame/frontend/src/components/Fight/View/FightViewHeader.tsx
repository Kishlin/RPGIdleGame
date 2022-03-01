import { Grid, Typography } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import FightParticipantView from './FightParticipantView';

function FightViewHeader({
    initiatorResult,
    opponentResult,
    initiator,
    opponent,
}: FightViewHeaderProps) {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <Grid container direction="row" columns={{ xs: 12, sm: 11 }} alignItems="center">
            <Grid item xs={12} sm={5}>
                <FightParticipantView result={initiatorResult} participant={initiator} />
            </Grid>
            <Grid item xs={12} sm={1} sx={{ my: 1 }}>
                <Typography textAlign="center">{t('components.fight.vs')}</Typography>
            </Grid>
            <Grid item xs={12} sm={5}>
                <FightParticipantView result={opponentResult} participant={opponent} />
            </Grid>
        </Grid>
    );
}

export default FightViewHeader;
