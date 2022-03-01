import { Delete, Edit, RemoveRedEye } from '@mui/icons-material';
import { Grid } from '@mui/material';
import React from 'react';

import NavigationIcon from '../../Navigation/NavigationIcon';

function CharacterCardActionNavigation({ id }: CharacterCardActionNavigation): JSX.Element {
    return (
        <Grid item container direction="row" justifyContent="space-between">
            <Grid item>
                <NavigationIcon
                    to={`/character/${id}/details`}
                    icon={<RemoveRedEye />}
                    label="components.character.links.details"
                />
                <NavigationIcon
                    to={`/character/${id}/skill-points`}
                    icon={<Edit />}
                    label="components.character.links.skillPoints"
                />
            </Grid>
            <NavigationIcon
                to={`/character/${id}/delete`}
                icon={<Delete color="error" />}
                label="components.character.links.delete"
            />
        </Grid>
    );
}

export default CharacterCardActionNavigation;
