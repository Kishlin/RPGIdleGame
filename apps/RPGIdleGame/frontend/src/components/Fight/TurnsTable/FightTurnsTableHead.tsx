import { TableCell, TableHead, TableRow } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

function FightTurnsTableHead(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <TableHead>
            <TableRow>
                <TableCell>{t('entities.fight.turns.header.index')}</TableCell>
                <TableCell>{t('entities.fight.turns.header.attacker')}</TableCell>
                <TableCell>{t('entities.fight.turns.header.diceRoll')}</TableCell>
                <TableCell>{t('entities.fight.turns.header.defenderDefense')}</TableCell>
                <TableCell>{t('entities.fight.turns.header.damage')}</TableCell>
                <TableCell>{t('entities.fight.turns.header.defenderHealth')}</TableCell>
            </TableRow>
        </TableHead>
    );
}

export default FightTurnsTableHead;
