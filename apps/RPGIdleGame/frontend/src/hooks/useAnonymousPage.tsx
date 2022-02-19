import { useContext, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

import { UserContext } from '../context/UserContext';

function useAnonymousPage() {
    const { isAuthenticated } = useContext<UserContextType>(UserContext);

    const navigate = useNavigate();

    useEffect(
        () => {
            if (true === isAuthenticated) {
                navigate('/');
            }
        },
        [],
    );
}

export default useAnonymousPage;
