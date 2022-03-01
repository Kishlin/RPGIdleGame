import { CardHeader } from '@mui/material';
import { Add } from '@mui/icons-material';
import React from 'react';

import NavigationIcon from '../../Navigation/NavigationIcon';

function CharacterCardHeader({ id, name }: CharacterCardHeaderProps): JSX.Element {
    return (
        <CardHeader
            title={name}
            action={(
                <NavigationIcon
                    to={`/character/${id}/details`}
                    icon={<Add />}
                    label="components.character.links.details"
                />
            )}
        />
    );
}

export default CharacterCardHeader;
