import React from 'react';
import { Button } from '@mui/material';

function ButtonSubmit({
    text,
    disabled,
    onFormSubmit,
    endIcon,
}: ButtonSubmitProps): JSX.Element {
    return (
        <Button
            variant="contained"
            disabled={disabled}
            onClick={onFormSubmit}
            endIcon={endIcon}
        >
            {text}
        </Button>
    );
}

export default ButtonSubmit;
