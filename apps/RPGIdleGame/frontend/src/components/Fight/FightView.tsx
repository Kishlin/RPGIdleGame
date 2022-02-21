import React, { useContext } from 'react';
import { Grid, Stack, Typography } from '@mui/material';
import FightParticipantView from './FightParticipantView';

import { LangContext } from '../../context/LangContext';
import FightTurnsView from './FightTurnsView';

function FightView({ fight }: FightViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const bottom = null === fight.winner_id
        ? <Typography>{t('entities.fight.draw')}</Typography>
        : <FightTurnsView turns={fight.turns} />;

    return (
        <Stack spacing={3}>
            <Grid container direction="row" columns={{ xs: 12, sm: 11 }} alignItems="center">
                <Grid item xs={12} sm={5}>
                    <FightParticipantView participant={fight.initiator} />
                </Grid>
                <Grid item xs={12} sm={1}>
                    <Typography>{t('entities.fight.vs')}</Typography>
                </Grid>
                <Grid item xs={12} sm={5}>
                    <FightParticipantView participant={fight.opponent} />
                </Grid>
            </Grid>
            { bottom }
        </Stack>
    );
}

export default FightView;
