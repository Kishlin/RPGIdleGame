import { TextField } from '@mui/material';
import React, { ChangeEvent, useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

function FieldUsername({ username, changeUsername }: FieldUsernameProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <TextField
            required
            fullWidth
            value={username}
            label={t('pages.signup.form.username.label')}
            onChange={(event: ChangeEvent<HTMLInputElement>) => changeUsername(event.target.value)}
        />
    );
}

export default FieldUsername;
