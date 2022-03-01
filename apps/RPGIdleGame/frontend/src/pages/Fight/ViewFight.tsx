import React, { useContext, useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

import { UserContext } from '../../context/UserContext';

import LayoutAuthenticated from '../../components/Layout/LayoutAuthenticated';
import FightView from '../../components/Fight/View/FightView';
import Loading from '../../components/Loading/Loading';

import useAuthenticatedPage from '../../hooks/useAuthenticatedPage';
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

    const content = null === fight ? <Loading /> : <FightView fight={fight} />;

    return (
        <LayoutAuthenticated>
            {content}
        </LayoutAuthenticated>
    );
}

export default ViewFight;
