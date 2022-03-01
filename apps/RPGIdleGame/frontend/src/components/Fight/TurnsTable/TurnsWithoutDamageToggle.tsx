import { Checkbox, FormControlLabel, FormGroup } from '@mui/material';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

function TurnsWithoutDamageToggle(
    { value, setValue }: TurnsWithoutDamageToggleProps,
): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <FormGroup>
            <FormControlLabel
                control={<Checkbox onChange={() => setValue(!value)} />}
                label={t('components.fight.turns.checkbox.allTurns')}
            />
        </FormGroup>
    );
}

export default TurnsWithoutDamageToggle;
