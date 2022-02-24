import React, { useContext, useEffect, useState } from 'react';
import { RemoveRedEye } from '@mui/icons-material';
import {
    styled,
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow, Tooltip,
} from '@mui/material';

import { LangContext } from '../../context/LangContext';

import Loading from '../Loading/Loading';

import allFightsOfFighter from '../../api/fight/allFightsOfFighter';
import NavigationIcon from '../Navigation/NavigationIcon';

const StyledResultTableCell = styled(TableCell)(({ theme }) => ({
   '&.result-win': {
        color: theme.palette.success.main,
   },
   '&.result-loss': {
       color: theme.palette.error.main,
   },
}));

const StyledTableRow = styled(TableRow)(({ theme }) => ({
    '&:nth-of-type(odd)': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

function FightShortList({ fighterId }: FightShortListProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [fights, setFights] = useState<FightShort[]>([]);

    const loadAllFightsForFighter = () => {
        const onSuccess = (fightsFromApi: FightShort[]) => {
            setFights(fightsFromApi.reverse());
            setIsLoading(false);
        };

        allFightsOfFighter(fighterId, onSuccess);
    };

    useEffect(loadAllFightsForFighter, []);

    if (isLoading) {
        return <Loading />;
    }

    const resultKey = (winnerId: null|string) => {
        if (null === winnerId) return 'draw';

        return winnerId === fighterId ? 'win' : 'loss';
    };

    const tableRows = fights.reverse().map((fight: FightShort) => {
        const result = resultKey(fight.winner_id);

        return (
            <StyledTableRow key={fight.id}>
                <TableCell>{fight.initiator_name}</TableCell>
                <TableCell>{fight.initiator_rank}</TableCell>
                <TableCell>{fight.opponent_name}</TableCell>
                <TableCell>{fight.opponent_rank}</TableCell>
                <StyledResultTableCell className={`result-${result}`}>
                    {t(`entities.shortFight.results.${result}`)}
                </StyledResultTableCell>
                <TableCell>
                    <NavigationIcon
                        icon={<RemoveRedEye />}
                        to={`/fight/${fight.id}`}
                        label="entities.shortFight.detailsLink"
                    />
                </TableCell>
            </StyledTableRow>
        );
    });

    return (
        <TableContainer>
            <Table>
                <TableHead>
                    <TableRow>
                        <TableCell>{t('entities.shortFight.initiatorName')}</TableCell>
                        <Tooltip title={t('entities.shortFight.tooltipRank')}>
                            <TableCell>{t('entities.shortFight.initiatorRank')}</TableCell>
                        </Tooltip>
                        <TableCell>{t('entities.shortFight.opponentName')}</TableCell>
                        <Tooltip title={t('entities.shortFight.tooltipRank')}>
                            <TableCell>{t('entities.shortFight.opponentRank')}</TableCell>
                        </Tooltip>
                        <TableCell>{t('entities.shortFight.result')}</TableCell>
                        <TableCell />
                    </TableRow>
                </TableHead>
                <TableBody>{tableRows}</TableBody>
            </Table>
        </TableContainer>
    );
}

export default FightShortList;
