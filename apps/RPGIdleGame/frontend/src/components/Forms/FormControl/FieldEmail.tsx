import { TextField } from '@mui/material';
import React, { ChangeEvent } from 'react';

function FieldEmail({
    label,
    email,
    error,
    changeEmail,
}: FieldEmailProps): JSX.Element {
    return (
        <TextField
            required
            fullWidth
            label={label}
            value={email}
            error={null !== error}
            helperText={null === error ? '' : error}
            onChange={(event: ChangeEvent<HTMLInputElement>) => changeEmail(event.target.value)}
        />
    );
}

export default FieldEmail;
