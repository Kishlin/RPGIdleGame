import React, { useContext } from 'react';
import { tableCellClasses } from '@mui/material/TableCell';
import {
    CardContent,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableRow,
    Typography,
} from '@mui/material';

import { LangContext } from '../../../context/LangContext';

function CharacterCardContent({ character }: CharacterCardContentProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const characterStats = t(
        'components.character.meta.stats',
        {
            fights: character.fights_count,
            wins: character.wins_count,
            draws: character.draws_count,
            losses: character.losses_count,
        },
    );

    const tableRows = ['health', 'attack', 'defense', 'magik'].map(
        (row) => (
            <TableRow key={row}>
                <TableCell>{t(`entities.character.${row}`)}</TableCell>
                <TableCell>{character[row]}</TableCell>
            </TableRow>
        ),
    );

    return (
        <CardContent>
            <Typography>{t('components.character.meta.rank', { rank: character.rank })}</Typography>
            <Typography>{t('components.character.meta.skillPoints', { skillPoints: character.skill_points })}</Typography>
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
            <Typography>{characterStats}</Typography>
        </CardContent>
    );
}

export default CharacterCardContent;
