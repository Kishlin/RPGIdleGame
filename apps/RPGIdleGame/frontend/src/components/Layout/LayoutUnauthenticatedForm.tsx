import { Grid } from '@mui/material';
import React from 'react';

import LayoutUnauthenticated from './LayoutUnauthenticated';

function LayoutUnauthenticatedForm({ form, navigationButton }: LayoutUnauthenticatedFormProps): JSX.Element {
    return (
        <LayoutUnauthenticated>
            <Grid container spacing={3}>
                <Grid item xs={12}>
                    {form}
                </Grid>
                <Grid item xs={12} container justifyContent="center">
                    {navigationButton}
                </Grid>
            </Grid>
        </LayoutUnauthenticated>
    );
}

export default LayoutUnauthenticatedForm;
