import React from 'react';
import { Card } from '@mui/material';

import CharacterCardHeader from './CharacterCardHeader';
import CharacterCardContent from './CharacterCardContent';
import CharacterCardActions from './CharacterCardActions';

function CharacterCard({ character, withActions }: CharacterCardProps): JSX.Element {
    return (
        <Card>
            <CharacterCardHeader name={character.name} id={character.id} />
            <CharacterCardContent character={character} />
            {
                withActions
                    ? <CharacterCardActions id={character.id} availableAsOf={character.available_as_of} />
                    : <noscript />
            }
        </Card>
    );
}

CharacterCard.defaultProps = {
    withActions: true,
};

export default CharacterCard;
