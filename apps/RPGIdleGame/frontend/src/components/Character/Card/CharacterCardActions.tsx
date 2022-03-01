import React from 'react';
import { CardActions, Grid } from '@mui/material';

import CharacterCardActionNavigation from './CharacterCardActionNavigation';
import CharacterCardActionFight from './CharacterCardActionFight';

function CharacterCardActions({ id, availableAsOf, withActions }: CharacterCardActionsProps): JSX.Element {
    if (false === withActions) {
        return <noscript />;
    }

    return (
        <CardActions>
            <Grid container direction="column">
                <CharacterCardActionFight id={id} availableAsOf={availableAsOf} />
                <CharacterCardActionNavigation id={id} />
            </Grid>
        </CardActions>
    );
}

export default CharacterCardActions;
