import React, { ChangeEvent, MouseEvent, useContext } from 'react';
import {
    FormControl,
    IconButton,
    InputAdornment,
    InputLabel,
    OutlinedInput,
} from '@mui/material';
import { Visibility, VisibilityOff } from '@mui/icons-material';

import { LangContext } from '../../../context/LangContext';

function FieldPassword({
   password,
   changePassword,
   showPasswordAsText,
   togglePasswordVisibility,
}: FieldPasswordProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const onMouseDown = (event: MouseEvent<HTMLButtonElement>|MouseEvent<HTMLAnchorElement>) => event.preventDefault();

    return (
        <FormControl required fullWidth sx={{ m: 1 }} variant="outlined">
            <InputLabel htmlFor="outlined-adornment-password">
                {t('pages.signup.form.password.label')}
            </InputLabel>
            <OutlinedInput
                value={password}
                id="outlined-adornment-password"
                type={showPasswordAsText ? 'text' : 'password'}
                onChange={(event: ChangeEvent<HTMLInputElement>) => changePassword(event.target.value)}
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
                label={t('pages.signup.form.password.label')}
            />
        </FormControl>
    );
}

export default FieldPassword;
