import React, { useContext } from 'react';
import { Grid, Stack, Typography } from '@mui/material';
import FightParticipantView from './FightParticipantView';

import { LangContext } from '../../context/LangContext';
import FightTurnsView from './FightTurnsView';
import NavigationButton from '../Navigation/NavigationButton';

function resultsForParticipants(fight: Fight): ['win'|'draw'|'loss', 'win'|'draw'|'loss'] {
    if (null === fight.winner_id) {
        return ['draw', 'draw'];
    }

    if (fight.winner_id === fight.initiator.character_id) {
        return ['win', 'loss'];
    }

    return ['loss', 'win'];
}

function FightView({ fight }: FightViewProps): JSX.Element {
    const { t, lang } = useContext<LangContextType>(LangContext);

    const [resultInitiator, resultOpponent] = resultsForParticipants(fight);

    const turnsView = null === fight.winner_id
        ? <Typography>{t('entities.fight.draw')}</Typography>
        : <FightTurnsView turns={fight.turns} />;

    return (
        <Stack spacing={3}>
            <Typography textAlign="center">{(new Date(fight.fight_date * 1000).toLocaleString(lang))}</Typography>
            <Grid container direction="row" columns={{ xs: 12, sm: 11 }} alignItems="center">
                <Grid item xs={12} sm={5}>
                    <FightParticipantView result={resultInitiator} participant={fight.initiator} />
                </Grid>
                <Grid item xs={12} sm={1} sx={{ my: 1 }}>
                    <Typography textAlign="center">{t('entities.fight.vs')}</Typography>
                </Grid>
                <Grid item xs={12} sm={5}>
                    <FightParticipantView result={resultOpponent} participant={fight.opponent} />
                </Grid>
            </Grid>
            <NavigationButton variant="text" text="pages.fights.buttons.homepage" to="/" />
            { turnsView }
        </Stack>
    );
}

export default FightView;
