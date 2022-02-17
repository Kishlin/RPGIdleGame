import React, { useContext } from 'react';
import { Button } from '@mui/material';
import { useNavigate } from 'react-router-dom';

import { LangContext } from '../../context/LangContext';

const defaultProps = {
    variant: 'contained',
    color: 'primary',
};

function NavigationButton({
    text,
    to,
    color,
    variant,
}: NavigationButtonComponentProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);
    const navigate = useNavigate();

    return (
        <Button onClick={() => navigate(to)} color={color} variant={variant}>
            { t(text) }
        </Button>
    );
}

NavigationButton.defaultProps = defaultProps;

export default NavigationButton;
