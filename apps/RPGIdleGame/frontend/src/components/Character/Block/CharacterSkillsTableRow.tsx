import { TableCell, TableRow } from '@mui/material';
import React from 'react';

function CharacterSkillsTableRow({ label, value }: CharacterSkillsTableRowProps): JSX.Element {
    return (
        <TableRow>
            <TableCell>{label}</TableCell>
            <TableCell align="right">{value}</TableCell>
        </TableRow>
    );
}

export default CharacterSkillsTableRow;
