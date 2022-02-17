import { TextField } from '@mui/material';
import React, { ChangeEvent } from 'react';

function FieldUsername({ label, username, changeUsername }: FieldUsernameProps): JSX.Element {
    return (
        <TextField
            required
            fullWidth
            label={label}
            value={username}
            onChange={(event: ChangeEvent<HTMLInputElement>) => changeUsername(event.target.value)}
        />
    );
}

export default FieldUsername;
