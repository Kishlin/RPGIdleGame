import React, { ReactElement, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { Grid, Typography } from '@mui/material';

import { LangContext } from '../../context/LangContext';

import ChangeLangButton from '../i18n/ChangeLangButton';

function Header({ menu }: { menu?: ReactElement }): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

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
                <Grid item xs container direction="row">
                    <Typography variant="h6" onClick={() => navigate('/')} style={{ cursor: 'pointer' }} sx={{ mx: 5 }}>
                        { t('fragments.header.title') }
                    </Typography>

                    {menu}
                </Grid>

                <Grid item xs sx={{ mr: 5 }}>
                    <ChangeLangButton />
                </Grid>
            </Grid>
        </header>
    );
}

Header.defaultProps = {
    menu: (<noscript />),
};

export default Header;
