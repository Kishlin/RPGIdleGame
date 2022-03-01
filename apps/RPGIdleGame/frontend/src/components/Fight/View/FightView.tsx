import React, { useContext } from 'react';
import { Stack, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import NavigationButton from '../../Navigation/NavigationButton';
import FightViewHeader from './FightViewHeader';
import FightTurnsView from './FightTurnsView';

import resultsForParticipants from '../../../tools/resultsForParticipants';

function FightView({ fight }: FightViewProps): JSX.Element {
    const { lang } = useContext<LangContextType>(LangContext);

    const [resultInitiator, resultOpponent] = resultsForParticipants(fight);

    return (
        <Stack spacing={3}>
            <Typography textAlign="center">{(new Date(fight.fight_date * 1000).toLocaleString(lang))}</Typography>
            <FightViewHeader
                initiatorResult={resultInitiator}
                opponentResult={resultOpponent}
                initiator={fight.initiator}
                opponent={fight.opponent}
            />
            <NavigationButton variant="text" text="pages.fights.buttons.homepage" to="/" />
            <FightTurnsView winnerId={fight.winner_id} turns={fight.turns} />
        </Stack>
    );
}

export default FightView;
