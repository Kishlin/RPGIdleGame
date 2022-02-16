import React, { ChangeEvent, MouseEvent, useContext } from 'react';
import {
    FormControl, FormHelperText,
    IconButton,
    InputAdornment,
    InputLabel,
    OutlinedInput,
} from '@mui/material';
import { Visibility, VisibilityOff } from '@mui/icons-material';

import { LangContext } from '../../../context/LangContext';

function FieldPasswordCheck({
    passwordCheck,
    hasError,
    changePasswordCheck,
    showPasswordAsText,
    togglePasswordVisibility,
}: FieldPasswordCheckProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const onMouseDown = (event: MouseEvent<HTMLButtonElement>|MouseEvent<HTMLAnchorElement>) => event.preventDefault();

    return (
        <FormControl required fullWidth sx={{ m: 1 }} variant="outlined">
            <InputLabel htmlFor="outlined-adornment-password-check">
                {t('pages.signup.form.passwordCheck.label')}
            </InputLabel>
            <OutlinedInput
                error={hasError}
                value={passwordCheck}
                id="outlined-adornment-password-check"
                type={showPasswordAsText ? 'text' : 'password'}
                onChange={(event: ChangeEvent<HTMLInputElement>) => changePasswordCheck(event.target.value)}
                endAdornment={(
                    <InputAdornment position="end">
                        <IconButton
                            aria-label="toggle password visibility"
                            onClick={togglePasswordVisibility}
                            onMouseDown={onMouseDown}
                            edge="end"
                        >
                            {showPasswordAsText ? <VisibilityOff /> : <Visibility />}
                        </IconButton>
                    </InputAdornment>
                )}
                label={t('pages.signup.form.passwordCheck.label')}
            />
            <FormHelperText
                error={hasError}
                hidden={false === hasError}
                about="outlined-adornment-password-check"
            >
                {t('pages.signup.form.passwordCheck.error')}
            </FormHelperText>
        </FormControl>
    );
}

export default FieldPasswordCheck;
