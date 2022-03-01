import React, { useContext } from 'react';
import { Button } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import NavigationButton from '../../Navigation/NavigationButton';

import characterIsReadyToFight from '../../../tools/characterIsReadyToFight';
import timeFormat from '../../../tools/dates';

function CharacterCardActionFight({ id, availableAsOf }: CharacterCardActionFightProps): JSX.Element {
    if (characterIsReadyToFight(availableAsOf)) {
        return <NavigationButton text="components.character.links.fight" to={`/character/${id}/fight`} />;
    }

    const { t, lang } = useContext<LangContextType>(LangContext);

    return (
        <Button color="error" variant="text">
            {t('entities.character.resting', { date: timeFormat(availableAsOf, lang) })}
        </Button>
    );
}

export default CharacterCardActionFight;
