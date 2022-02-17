import React, { ChangeEvent, MouseEvent } from 'react';
import {
    FormControl, FormHelperText,
    IconButton,
    InputAdornment,
    InputLabel,
    OutlinedInput,
} from '@mui/material';
import { Visibility, VisibilityOff } from '@mui/icons-material';

function FieldPassword({
    label,
    error,
    password,
    changePassword,
    showPasswordAsText,
    togglePasswordVisibility,
}: FieldPasswordProps): JSX.Element {
    const onMouseDown = (event: MouseEvent<HTMLButtonElement>|MouseEvent<HTMLAnchorElement>) => event.preventDefault();

    return (
        <FormControl required fullWidth sx={{ m: 1 }} variant="outlined">
            <InputLabel error={null !== error} htmlFor="outlined-adornment-password-check">{label}</InputLabel>
            <OutlinedInput
                label={label}
                error={null !== error}
                value={password}
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
            />
            <FormHelperText
                error={null !== error}
                hidden={null === error}
                about="outlined-adornment-password-check"
            >
                {error}
            </FormHelperText>
        </FormControl>
    );
}

export default FieldPassword;
