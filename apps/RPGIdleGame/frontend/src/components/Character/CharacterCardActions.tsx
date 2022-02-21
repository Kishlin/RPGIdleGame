import React, { useContext } from 'react';
import { CardActions, Grid, IconButton } from '@mui/material';
import { Delete, Edit, RemoveRedEye } from '@mui/icons-material';

import { LangContext } from '../../context/LangContext';
import NavigationButton from '../Navigation/NavigationButton';
import NavigationIcon from '../Navigation/NavigationIcon';

function CharacterCardActions({ id }: CharacterCardActionsProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <CardActions>
            <Grid container direction="column">
                <NavigationButton text="components.character.links.fight" to={`/character/${id}/fight`} />
                <Grid item container direction="row" justifyContent="space-between">
                    <Grid item>
                        <IconButton aria-label={t('components.character.links.details')}>
                            <RemoveRedEye />
                        </IconButton>
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
