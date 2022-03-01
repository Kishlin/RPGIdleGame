import React, { useContext } from 'react';
import { Button, CardActions, Grid } from '@mui/material';
import { Delete, Edit, RemoveRedEye } from '@mui/icons-material';

import { LangContext } from '../../../context/LangContext';

import NavigationButton from '../../Navigation/NavigationButton';
import NavigationIcon from '../../Navigation/NavigationIcon';

import characterIsReadyToFight from '../../../tools/characterIsReadyToFight';
import timeFormat from '../../../tools/dates';

function CharacterCardActions({ id, availableAsOf }: CharacterCardActionsProps): JSX.Element {
    const { t, lang } = useContext<LangContextType>(LangContext);

    let mainAction;

    if (characterIsReadyToFight(availableAsOf)) {
        mainAction = <NavigationButton text="components.character.links.fight" to={`/character/${id}/fight`} />;
    } else {
        mainAction = (
            <Button color="error" variant="text">
                {t('entities.character.resting', { date: timeFormat(availableAsOf, lang) })}
            </Button>
        );
    }

    return (
        <CardActions>
            <Grid container direction="column">
                { mainAction }
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
