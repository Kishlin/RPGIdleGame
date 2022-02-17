import React, { useContext, useState } from 'react';
import { Stack, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import isAValidEmail from '../../../tools/isAValidEmail';

import FieldEmail from '../FormControl/FieldEmail';
import FieldUsername from '../FormControl/FieldUsername';
import FieldPassword from '../FormControl/FieldPassword';
import ButtonSubmit from '../FormControl/ButtonSubmit';

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

            <FieldUsername
                username={username}
                changeUsername={setUsername}
                label={t('pages.signup.form.username.label')}
            />

            <FieldEmail
                email={email}
                changeEmail={setEmail}
                label={t('pages.signup.form.email.label')}
                error={emailError ? t('pages.signup.form.email.error') : null}
            />

            <FieldPassword
                label={t('pages.signup.form.password.label')}
                error={null}
                password={password}
                changePassword={setPassword}
                showPasswordAsText={showPassword}
                togglePasswordVisibility={handleClickShowPassword}
            />

            <FieldPassword
                label={t('pages.signup.form.passwordCheck.label')}
                error={passwordMatchError ? 'pages.signup.form.passwordCheck.error' : null}
                password={passwordCheck}
                changePassword={setPasswordCheck}
                showPasswordAsText={showPassword}
                togglePasswordVisibility={handleClickShowPassword}
            />

            <Typography color={null === error ? 'default' : 'error'} variant="subtitle1">
                {t(subtitle)}
            </Typography>

            <ButtonSubmit
                text={t('pages.signup.form.buttons.submit')}
                disabled={formIsIncomplete || isLoading}
                onFormSubmit={() => onFormSubmit({ email, username, password })}
            />
        </Stack>
    );
}

export default SignUpForm;
