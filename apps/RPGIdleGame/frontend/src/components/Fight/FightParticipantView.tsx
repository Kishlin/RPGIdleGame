import React, { useContext } from 'react';
import { tableCellClasses } from '@mui/material/TableCell';
import {
    Grid,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableRow, Typography,
} from '@mui/material';

import { LangContext } from '../../context/LangContext';

function FightParticipantView({ participant }: FightParticipantViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const tableRows = ['health', 'attack', 'defense', 'magik'].map(
        (row) => (
            <TableRow key={row}>
                <TableCell>{t(`entities.fight.participant.${row}`)}</TableCell>
                <TableCell>{participant[row]}</TableCell>
            </TableRow>
        ),
    );

    const headline = t(
        'entities.fight.participant.headline',
        {
            player: participant.account_username,
            fighter: participant.character_name,
            rank: participant.rank,
        },
    );

    return (
        <Grid container direction="column" alignItems="center">
            <Typography>{headline}</Typography>
            <TableContainer>
                <Table
                    size="small"
                    sx={{
                        [`& .${tableCellClasses.root}`]: {
                            borderBottom: 'none',
                        },
                    }}
                >
                    <TableBody>{tableRows}</TableBody>
                </Table>
            </TableContainer>
        </Grid>
    );
}

export default FightParticipantView;
