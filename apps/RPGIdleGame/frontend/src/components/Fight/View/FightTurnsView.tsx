import { Typography } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import FightTurnsDetailedView from '../TurnsTable/FightTurnsDetailedView';

function FightTurnsView({ turns, winnerId }: FightTurnsViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    if (null === winnerId) {
        return <Typography>{t('components.fight.draw')}</Typography>;
    }

    return <FightTurnsDetailedView turns={turns} />;
}

export default FightTurnsView;
