import React, { useContext, useState } from 'react';
import { Stack, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import FieldEmail from './FieldEmail';
import FieldUsername from './FieldUsername';
import FieldPassword from './FieldPassword';
import FieldPasswordCheck from './FieldPasswordCheck';
import ButtonSubmit from './ButtonSubmit';

function SignUpForm({ onFormSubmit }: SignUpFormProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [email, setEmail] = useState<string>('');
    const [username, setUsername] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const [passwordCheck, setPasswordCheck] = useState<string>('');

    const [showPassword, setShowPassword] = useState<boolean>(false);

    const handleClickShowPassword = () => {
        setShowPassword(!showPassword);
    };

    const emailRegex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const emailIsValid = (str: string): boolean => null !== String(str).toLowerCase().match(emailRegex);

    const emailError = '' !== email && false === emailIsValid(email);
    const passwordMatchError = '' !== password && '' !== passwordCheck && passwordCheck !== password;

    const formIsIncomplete = '' === email || '' === username || '' === password || '' === passwordCheck || emailError || passwordMatchError;

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

            <Typography variant="subtitle1">
                {t(`pages.signup.form.infos.${formIsIncomplete ? 'incomplete' : 'complete'}`)}
            </Typography>

            <ButtonSubmit
                disabled={formIsIncomplete}
                onFormSubmit={() => onFormSubmit({ email, username, password })}
            />
        </Stack>
    );
}

export default SignUpForm;
