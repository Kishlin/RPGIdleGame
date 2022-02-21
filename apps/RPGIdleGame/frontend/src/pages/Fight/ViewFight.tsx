import React, { useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { Container } from '@mui/material';

import { UserContext } from '../../context/UserContext';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';
import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';
import Loading from '../../components/Loading/Loading';
import viewFightUsingFetch from '../../api/fight/viewFight';

function ViewFight(): JSX.Element {
    useAuthenticatedPage();

    const { fights, storeFight } = useContext<UserContextType>(UserContext);

    const [fight, setFight] = useState<Fight|null>(null);

    const { id } = useParams();

    useEffect(
        () => {
            if (fights[id] !== undefined) {
                setFight(fights[id]);
            } else {
                viewFightUsingFetch(id, (fightFromApi: Fight) => storeFight(fightFromApi));
            }
        },
        [fights],
    );

    const content = null === fight ? <Loading /> : <p>{fight.id}</p>;

    return (
        <LayoutAuthenticated>
            <Container maxWidth="sm">
                {content}
            </Container>
        </LayoutAuthenticated>
    );
}

export default ViewFight;
