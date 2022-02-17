import React from 'react';
import { Grid } from '@mui/material';

import Loading from '../components/Loading/Loading';

function AppLoading(): JSX.Element {
    return (
        <Grid container direction="column" alignItems="center" justifyContent="center" height="100vh">
            <Grid item width="100vw">
                <Loading />
            </Grid>
        </Grid>
    );
}

export default AppLoading;
