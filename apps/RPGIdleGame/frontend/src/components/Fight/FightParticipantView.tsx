import React, { useContext } from 'react';
import { tableCellClasses } from '@mui/material/TableCell';
import {
    Grid, styled,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableRow, Typography,
} from '@mui/material';

import { LangContext } from '../../context/LangContext';

const ResultTypography = styled(Typography)(({ theme }) => ({
    '&.win': {
        color: theme.palette.success.main,
    },
    '&.loss': {
        color: theme.palette.error.main,
    },
}));

function FightParticipantView({ participant, result }: FightParticipantViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const resultHeader = 'draw' !== result
        ? <ResultTypography className={result}>{t(`entities.fight.participant.headers.${result}`)}</ResultTypography>
        : <noscript />;

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
            {resultHeader}
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
