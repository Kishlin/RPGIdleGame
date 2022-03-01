import { TableCell, TableHead, TableRow } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

function FightTurnsTableHead(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <TableHead>
            <TableRow>
                <TableCell>{t('entities.fight.turns.index')}</TableCell>
                <TableCell>{t('entities.fight.turns.attacker')}</TableCell>
                <TableCell>{t('entities.fight.turns.diceRoll')}</TableCell>
                <TableCell>{t('entities.fight.turns.defenderDefense')}</TableCell>
                <TableCell>{t('entities.fight.turns.damage')}</TableCell>
                <TableCell>{t('entities.fight.turns.defenderHealth')}</TableCell>
            </TableRow>
        </TableHead>
    );
}

export default FightTurnsTableHead;
