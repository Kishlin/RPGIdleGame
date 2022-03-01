import { TableCell } from '@mui/material';
import React from 'react';

import StyledFightTurnsTableRow from './StyledFightTurnsTableRow';

function FightTurnsTableRow({ turn }: FightTurnsTableRowProps): JSX.Element {
    return (
        <StyledFightTurnsTableRow className={`index-modulo-${turn.index % 2}`}>
            <TableCell>{turn.index + 1}</TableCell>
            <TableCell>{turn.character_name}</TableCell>
            <TableCell>{turn.attacker_dice_roll}</TableCell>
            <TableCell>{turn.defender_defense}</TableCell>
            <TableCell>{turn.damage_dealt}</TableCell>
            <TableCell>{turn.defender_health}</TableCell>
        </StyledFightTurnsTableRow>
    );
}

export default FightTurnsTableRow;
