import React from 'react';
import { Fade, Grid } from '@mui/material';

import Header from '../Fragments/Header';

function LayoutAuthenticated({ children }: { children: React.ReactElement }): JSX.Element {
    return (
        <>
            <Header />
            <Grid container direction="column" sx={{ mt: '5vh' }}>
                <Fade appear in easing={{ enter: 'ease-in' }}>
                    {children}
                </Fade>
            </Grid>
        </>
    );
}

export default LayoutAuthenticated;
