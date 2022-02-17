import React, { useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { Grid, Typography } from '@mui/material';

import { LangContext } from '../../context/LangContext';

import ChangeLangButton from '../i18n/ChangeLangButton';

function Header(): JSX.Element {
    const { t } = useContext(LangContext);

    const navigate = useNavigate();

    return (
        <header>
            <Grid
                container
                sx={{ mt: 2, mb: 2 }}
                direction="row"
                justifyContent="space-between"
                alignItems="center"
            >
                <Grid item xs sx={{ ml: 5 }}>
                    <Typography variant="h6" onClick={() => navigate('/')} style={{ cursor: 'pointer' }}>
                        { t('fragments.header.title') }
                    </Typography>
                </Grid>

                <Grid item xs sx={{ mr: 5 }}>
                    <ChangeLangButton />
                </Grid>
            </Grid>
        </header>
    );
}

export default Header;
