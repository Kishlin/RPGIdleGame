import React, { useContext } from 'react';
import {
    Box,
    Button,
    Fade,
    Grid,
} from '@mui/material';

import { UserContext } from '../../context/UserContext';
import { LangContext } from '../../context/LangContext';

import Header from '../Fragments/Header';

import logOutUsingFetch from '../../api/account/logOut';

function LayoutAuthenticated({ children }: { children: React.ReactNode }): JSX.Element {
    const { disconnect } = useContext<UserContextType>(UserContext);
    const { t } = useContext<LangContextType>(LangContext);

    const disconnectThroughApi = () => logOutUsingFetch(
        () => disconnect(),
    );

    return (
        <>
            <Header
                menu={
                    <Button onClick={disconnectThroughApi}>{ t('fragments.header.menu.disconnect') }</Button>
                }
            />
            <Grid container direction="column" sx={{ mt: '5vh' }}>
                <Fade appear in easing={{ enter: 'ease-in' }}>
                    <Box>
                        {children}
                    </Box>
                </Fade>
            </Grid>
        </>
    );
}

export default LayoutAuthenticated;
