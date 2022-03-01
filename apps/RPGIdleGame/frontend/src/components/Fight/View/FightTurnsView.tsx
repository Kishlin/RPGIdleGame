import React, { useContext, useState } from 'react';
import {
    Box,
    Checkbox,
    FormControlLabel,
    FormGroup,
    styled,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow,
    Typography,
} from '@mui/material';

import { LangContext } from '../../../context/LangContext';

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&.index-modulo-0': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

function FightTurnsView({ turns }: FightTurnsViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    if (0 === turns.length) {
        return <Typography>{t('entities.fight.noTurns')}</Typography>;
    }

    const [showTurnsWithoutDamage, setShowTurnsWithoutDamage] = useState<boolean>(false);

    const filterTurns = (turn: FightTurn) => showTurnsWithoutDamage || 0 < turn.damage_dealt;

    const turnToRow = (turn: FightTurn) => (
        <StyledTableRow key={turn.index} className={`index-modulo-${turn.index % 2}`}>
            <TableCell>{turn.index + 1}</TableCell>
            <TableCell>{turn.character_name}</TableCell>
            <TableCell>{turn.attacker_dice_roll}</TableCell>
            <TableCell>{turn.defender_defense}</TableCell>
            <TableCell>{turn.damage_dealt}</TableCell>
            <TableCell>{turn.defender_health}</TableCell>
        </StyledTableRow>
    );

    const tableRows = turns.filter(filterTurns).map(turnToRow);

    return (
        <Box>
            <FormGroup>
                <FormControlLabel
                    control={<Checkbox onChange={() => setShowTurnsWithoutDamage(!showTurnsWithoutDamage)} />}
                    label={t('entities.fight.turns.checkbox.allTurns')}
                />
            </FormGroup>
            <TableContainer>
                <Table>
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
                    <TableBody>{tableRows}</TableBody>
                </Table>
            </TableContainer>
        </Box>
    );
}

export default FightTurnsView;
