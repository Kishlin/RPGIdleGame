import { useContext, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

import { UserContext } from '../context/UserContext';

function useAuthenticatedPage() {
    const { isAuthenticated } = useContext<UserContextType>(UserContext);

    const navigate = useNavigate();

    useEffect(
        () => {
            if (false === isAuthenticated) {
                navigate('/');
            }
        },
        [],
    );
}

export default useAuthenticatedPage;
