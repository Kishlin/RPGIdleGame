import React, { useContext, useState } from 'react';
import { Box, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import TurnsWithoutDamageToggle from './TurnsWithoutDamageToggle';
import FightTurnsTable from './FightTurnsTable';

function FightTurnsDetailedView({ turns }: FightTurnsDetailedViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    if (0 === turns.length) {
        return <Typography>{t('components.fight.noTurns')}</Typography>;
    }

    const [showTurnsWithoutDamage, setShowTurnsWithoutDamage] = useState<boolean>(false);

    return (
        <Box>
            <TurnsWithoutDamageToggle setValue={setShowTurnsWithoutDamage} value={showTurnsWithoutDamage} />
            <FightTurnsTable showTurnsWithoutDamage={showTurnsWithoutDamage} turns={turns} />
        </Box>
    );
}

export default FightTurnsDetailedView;
