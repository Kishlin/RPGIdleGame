import React, { useContext, useState } from 'react';
import { Stack, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import isAValidEmail from '../../../tools/isAValidEmail';

import FieldEmail from './FieldEmail';
import FieldUsername from './FieldUsername';
import FieldPassword from './FieldPassword';
import FieldPasswordCheck from './FieldPasswordCheck';
import ButtonSubmit from './ButtonSubmit';

function SignUpForm({ onFormSubmit, error, isLoading }: SignUpFormProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [email, setEmail] = useState<string>('');
    const [username, setUsername] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const [passwordCheck, setPasswordCheck] = useState<string>('');

    const [showPassword, setShowPassword] = useState<boolean>(false);

    const handleClickShowPassword = () => {
        setShowPassword(!showPassword);
    };

    const emailError = '' !== email && false === isAValidEmail(email);
    const passwordMatchError = '' !== password && '' !== passwordCheck && passwordCheck !== password;

    const formIsIncomplete = '' === email || '' === username || '' === password || '' === passwordCheck || emailError || passwordMatchError;

    let subtitle;
    if (error) {
        subtitle = `pages.signup.form.errors.${error}`;
    } else {
         subtitle = `pages.signup.form.infos.${formIsIncomplete ? 'incomplete' : 'complete'}`;
    }

    return (
        <Stack spacing={3}>
            <Typography variant="h5">{t('pages.signup.form.title')}</Typography>

            <FieldUsername username={username} changeUsername={setUsername} />

            <FieldEmail email={email} hasError={emailError} changeEmail={setEmail} />

            <FieldPassword
                password={password}
                changePassword={setPassword}
                showPasswordAsText={showPassword}
                togglePasswordVisibility={handleClickShowPassword}
            />

            <FieldPasswordCheck
                passwordCheck={passwordCheck}
                hasError={passwordMatchError}
                changePasswordCheck={setPasswordCheck}
                showPasswordAsText={showPassword}
                togglePasswordVisibility={handleClickShowPassword}
            />

            <Typography color={null === error ? 'default' : 'error'} variant="subtitle1">
                {t(subtitle)}
            </Typography>

            <ButtonSubmit
                disabled={formIsIncomplete || isLoading}
                onFormSubmit={() => onFormSubmit({ email, username, password })}
            />
        </Stack>
    );
}

export default SignUpForm;
