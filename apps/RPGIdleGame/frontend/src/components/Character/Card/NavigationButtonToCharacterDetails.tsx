import { Add } from '@mui/icons-material';
import React from 'react';

import NavigationIcon from '../../Navigation/NavigationIcon';

function NavigationButtonToCharacterDetails({ id }: NavigationButtonToCharacterDetailsProps): JSX.Element {
    return (
        <NavigationIcon
            icon={<Add />}
            to={`/character/${id}/details`}
            label="components.character.links.details"
        />
    );
}

export default NavigationButtonToCharacterDetails;
