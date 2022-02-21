import React, { useContext } from 'react';
import { IconButton, Tooltip } from '@mui/material';
import { useNavigate } from 'react-router-dom';

import { LangContext } from '../../context/LangContext';

function NavigationIcon({
    icon,
    label,
    to,
}: NavigationIconComponentProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);
    const navigate = useNavigate();

    const translatedLabel = t(label);

    return (
        <Tooltip title={translatedLabel}>
            <IconButton onClick={() => navigate(to)} aria-label={translatedLabel}>
                { icon }
            </IconButton>
        </Tooltip>
    );
}

export default NavigationIcon;
