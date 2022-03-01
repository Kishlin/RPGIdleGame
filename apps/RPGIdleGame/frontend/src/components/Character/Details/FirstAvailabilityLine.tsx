import { Typography } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import NavigationButton from '../../Navigation/NavigationButton';

import characterIsReadyToFight from '../../../tools/characterIsReadyToFight';
import timeFormat from '../../../tools/dates';

function FirstAvailabilityLine({ character }: FirstAvailabilityLineProps): JSX.Element {
    const { t, lang } = useContext<LangContextType>(LangContext);

    if (characterIsReadyToFight(character.available_as_of)) {
        return (
            <NavigationButton
                variant="text"
                color="success"
                to={`/character/${character.id}/fight`}
                text="components.character.meta.available"
            />
        );
    }

    return (
        <Typography color="error">
            {t('components.character.meta.resting', { date: timeFormat(character.available_as_of, lang) })}
        </Typography>
    );
}

export default FirstAvailabilityLine;
