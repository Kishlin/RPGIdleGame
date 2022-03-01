import { Table, TableBody, TableContainer } from '@mui/material';
import React from 'react';

import FightTurnsTableHead from './FightTurnsTableHead';
import FightTurnsTableRow from './FightTurnsTableRow';

function FightTurnsTable({ turns, showTurnsWithoutDamage }: FightTurnsTableProps): JSX.Element {
    const filterTurnsPredicate = (turn: FightTurn) => showTurnsWithoutDamage || 0 < turn.damage_dealt;

    const turnToRow = (turn: FightTurn) => <FightTurnsTableRow key={turn.index} turn={turn} />;

    const tableBody = (
        <TableBody>
            {turns.filter(filterTurnsPredicate).map(turnToRow)}
        </TableBody>
    );

    return (
        <TableContainer>
            <Table>
                <FightTurnsTableHead />
                {tableBody}
            </Table>
        </TableContainer>
    );
}

export default FightTurnsTable;
