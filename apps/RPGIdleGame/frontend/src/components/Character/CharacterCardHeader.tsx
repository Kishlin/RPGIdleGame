import React from 'react';
import { CardHeader, IconButton } from '@mui/material';
import { Add } from '@mui/icons-material';

function CharacterCardHeader({ name }: CharacterCardHeaderProps): JSX.Element {
    return (
        <CardHeader
            title={name}
            action={(
                <IconButton aria-label="details">
                    <Add />
                </IconButton>
            )}
        />
    );
}

export default CharacterCardHeader;
