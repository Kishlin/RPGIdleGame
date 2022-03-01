import { Grid, Typography } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import FightParticipantResultHeader from './FightParticipantResultHeader';
import CharacterSkillsTable from '../../Character/Block/CharacterSkillsTable';

function FightParticipantView({ participant, result }: FightParticipantViewProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const headlineData = {
        player: participant.account_username,
        fighter: participant.character_name,
        rank: participant.rank,
    };

    return (
        <Grid container direction="column" alignItems="center">
            <FightParticipantResultHeader result={result} />
            <Typography>{t('entities.fight.participant.headline', headlineData)}</Typography>
            <CharacterSkillsTable characterSkills={{ ...participant }} />
        </Grid>
    );
}

export default FightParticipantView;
