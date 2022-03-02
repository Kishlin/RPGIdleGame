import React from 'react';
import { Container, Fade, Grid } from '@mui/material';

import Header from '../Fragments/Header';

function LayoutUnauthenticated({ children }: { children: React.ReactElement }): JSX.Element {
    return (
        <>
            <Header />
            <Grid container direction="column" sx={{ mt: '5vh' }}>
                <Fade appear in easing={{ enter: 'ease-in' }}>
                    <Container maxWidth="sm">
                        {children}
                    </Container>
                </Fade>
            </Grid>
        </>
    );
}

export default LayoutUnauthenticated;
