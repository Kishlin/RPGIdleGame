import React, { useContext, useState } from 'react';
import { Stack, Typography } from '@mui/material';
import { AccountCircle } from '@mui/icons-material';

import { LangContext } from '../../../context/LangContext';

import FieldText from '../FormControl/FieldText';
import FieldPassword from '../FormControl/FieldPassword';
import ButtonSubmit from '../FormControl/ButtonSubmit';

function LogInForm({ onFormSubmit, error, isLoading }: LogInFormProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [login, setLogin] = useState<string>('');
    const [password, setPassword] = useState<string>('');

    const [showPassword, setShowPassword] = useState<boolean>(false);

    const handleClickShowPassword = () => {
        setShowPassword(!showPassword);
    };

    const formIsIncomplete = '' === login || '' === password;

    return (
        <Stack spacing={3}>
            <Typography variant="h5">{t('pages.login.form.title')}</Typography>

            <FieldText
                type="text"
                value={login}
                changeValue={setLogin}
                label={t('pages.login.form.login.label')}
            />

            <FieldPassword
                label={t('pages.login.form.password.label')}
                error={null}
                password={password}
                changePassword={setPassword}
                showPasswordAsText={showPassword}
                togglePasswordVisibility={handleClickShowPassword}
            />

            <Typography color="error" hidden={null === error} variant="subtitle1">{error}</Typography>

            <ButtonSubmit
                text={t('pages.login.form.buttons.submit')}
                disabled={formIsIncomplete || isLoading}
                onFormSubmit={() => onFormSubmit({ login, password })}
                endIcon={<AccountCircle />}
            />
        </Stack>
    );
}

export default LogInForm;
