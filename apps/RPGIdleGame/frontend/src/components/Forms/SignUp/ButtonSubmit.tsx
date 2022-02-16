import React, { useContext } from 'react';
import { Button } from '@mui/material';
import AccountCircleIcon from '@mui/icons-material/AccountCircle';

import { LangContext } from '../../../context/LangContext';

function ButtonSubmit({ disabled, onFormSubmit }: ButtonSubmitProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <Button
            variant="contained"
            disabled={disabled}
            onClick={onFormSubmit}
            endIcon={<AccountCircleIcon />}
        >
            {t('pages.signup.form.buttons.submit')}
        </Button>
    );
}

export default ButtonSubmit;
