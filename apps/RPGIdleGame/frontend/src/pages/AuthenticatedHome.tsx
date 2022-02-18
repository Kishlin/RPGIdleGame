import React from 'react';

import LayoutAuthenticated from '../components/Layout/LayoutAuthenticated';
import NavigationButton from '../components/Navigation/NavigationButton';

function AuthenticatedHome(): JSX.Element {
    return (
        <LayoutAuthenticated>
            <p>Connected</p>

            <NavigationButton text="pages.home.authenticated.buttons.createCharacter" to="/new-character" />
        </LayoutAuthenticated>
    );
}

export default AuthenticatedHome;
