import React from 'react';
import { CardActions, Grid } from '@mui/material';
import { Delete, Edit, RemoveRedEye } from '@mui/icons-material';

import NavigationButton from '../Navigation/NavigationButton';
import NavigationIcon from '../Navigation/NavigationIcon';

function CharacterCardActions({ id }: CharacterCardActionsProps): JSX.Element {
    return (
        <CardActions>
            <Grid container direction="column">
                <NavigationButton text="components.character.links.fight" to={`/character/${id}/fight`} />
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
            </Grid>
        </CardActions>
    );
}

export default CharacterCardActions;
