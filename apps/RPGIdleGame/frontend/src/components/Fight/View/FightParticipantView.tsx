import React, { useContext } from 'react';
import { tableCellClasses } from '@mui/material/TableCell';
import {
    Grid,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableRow,
    Typography,
} from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import FightParticipantResultHeader from './FightParticipantResultHeader';

function FightParticipantView({ participant, result }: FightParticipantViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const headlineData = {
        player: participant.account_username,
        fighter: participant.character_name,
        rank: participant.rank,
    };

    const tableRows = ['health', 'attack', 'defense', 'magik'].map(
        (row) => (
            <TableRow key={row}>
                <TableCell>{t(`entities.fight.participant.${row}`)}</TableCell>
                <TableCell align="right">{participant[row]}</TableCell>
            </TableRow>
        ),
    );

    return (
        <Grid container direction="column" alignItems="center">
            <FightParticipantResultHeader result={result} />
            <Typography>{t('entities.fight.participant.headline', headlineData)}</Typography>
            <TableContainer sx={{ maxWidth: '250px' }}>
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
