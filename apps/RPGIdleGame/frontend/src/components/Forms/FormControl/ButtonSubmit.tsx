import React from 'react';
import { Button } from '@mui/material';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';

function ButtonSubmit({ text, disabled, onFormSubmit }: ButtonSubmitProps): JSX.Element {
    return (
        <Button
            variant="contained"
            disabled={disabled}
            onClick={onFormSubmit}
            endIcon={<AccountCircleIcon />}
        >
            {text}
        </Button>
    );
}

export default ButtonSubmit;
