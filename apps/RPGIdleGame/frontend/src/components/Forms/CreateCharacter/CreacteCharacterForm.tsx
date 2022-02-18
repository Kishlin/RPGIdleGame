import React, { useContext, useState } from 'react';
import { Stack, Typography } from '@mui/material';

import { LangContext } from '../../../context/LangContext';

import FieldText from '../FormControl/FieldText';
import ButtonSubmit from '../FormControl/ButtonSubmit';

function CreateCharacterForm({ onFormSubmit, error, isLoading }: CreateCharacterFormProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const [name, setName] = useState<string>('');

    const formIsIncomplete = '' === name;

    return (
        <Stack spacing={3}>
            <Typography variant="h5">{t('pages.character.title')}</Typography>

            <FieldText
                value={name}
                changeValue={setName}
                label={t('pages.character.formCreate.name.label')}
            />

            <Typography color="error" hidden={null === error} variant="subtitle1">{error}</Typography>

            <ButtonSubmit
                text={t('pages.character.formCreate.buttons.submit')}
                disabled={formIsIncomplete || isLoading}
                onFormSubmit={() => onFormSubmit({ name })}
            />
        </Stack>
    );
}

export default CreateCharacterForm;
