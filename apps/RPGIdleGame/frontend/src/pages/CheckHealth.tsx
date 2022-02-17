import React from 'react';
import { Navigate } from 'react-router-dom';

function CheckHealth(): JSX.Element {
    if ('development' !== process.env.NODE_ENV) {
        return <Navigate to="/" />;
    }

    return (
        <pre>
            {'{\n\t"frontend": true\n}'}
        </pre>
    );
}

export default CheckHealth;