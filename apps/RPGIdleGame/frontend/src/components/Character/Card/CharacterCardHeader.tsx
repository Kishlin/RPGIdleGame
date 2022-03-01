import { CardHeader } from '@mui/material';
import React from 'react';

import NavigationButtonToCharacterDetails from './NavigationButtonToCharacterDetails';

function CharacterCardHeader({ id, name }: CharacterCardHeaderProps): JSX.Element {
    return (
        <CardHeader
            title={name}
            action={<NavigationButtonToCharacterDetails id={id} />}
        />
    );
}

export default CharacterCardHeader;
