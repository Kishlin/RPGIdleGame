import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import FightParticipantResultTypography from './FightParticipantResultTypography';

function FightParticipantResultHeader({ result }: FightResultHeaderProps): JSX.Element {
    if ('draw' === result) {
        return <noscript />;
    }

    const { t } = useContext<LangContextType>(LangContext);

    return (
        <FightParticipantResultTypography className={result}>
            {t(`entities.fight.participant.headers.${result}`)}
        </FightParticipantResultTypography>
    );
}

export default FightParticipantResultHeader;
