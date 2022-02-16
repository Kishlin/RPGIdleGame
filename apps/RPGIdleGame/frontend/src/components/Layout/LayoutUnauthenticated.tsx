import React from 'react';
import { Grid } from '@mui/material';

import Header from '../Fragments/Header';

function LayoutUnauthenticated({ children }: { children: React.ReactNode }): JSX.Element {
    return (
        <>
            <Header />
            <Grid container direction="column">
                {children}
            </Grid>
        </>
    );
}

export default LayoutUnauthenticated;
