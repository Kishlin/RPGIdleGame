import React from 'react';

import LayoutAuthenticated from '../components/Layout/LayoutAuthenticated';

function AuthenticatedHome(): JSX.Element {
    return (
        <LayoutAuthenticated>
            <p>Connected</p>
        </LayoutAuthenticated>
    );
}

export default AuthenticatedHome;
