import { TextField } from '@mui/material';
import React, { ChangeEvent, useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

function FieldEmail({ email, hasError, changeEmail }: FieldEmailProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <TextField
            required
            fullWidth
            value={email}
            error={hasError}
            label={t('pages.signup.form.email.label')}
            helperText={hasError ? t('pages.signup.form.email.error') : ''}
            onChange={(event: ChangeEvent<HTMLInputElement>) => changeEmail(event.target.value)}
        />
    );
}

export default FieldEmail;
