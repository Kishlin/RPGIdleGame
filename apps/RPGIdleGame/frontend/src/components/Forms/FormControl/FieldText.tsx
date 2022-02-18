import { TextField } from '@mui/material';
import React, { ChangeEvent } from 'react';

function FieldText({
    label,
    value,
    changeValue,
    error,
    type,
}: FieldTextProps): JSX.Element {
    return (
        <TextField
            required
            fullWidth
            type={type}
            label={label}
            value={value}
            error={null !== error}
            helperText={null === error ? '' : error}
            onChange={(event: ChangeEvent<HTMLInputElement>) => changeValue(event.target.value)}
        />
    );
}

FieldText.defaultProps = {
    error: null,
    type: 'text',
};

export default FieldText;
