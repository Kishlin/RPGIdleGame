import { styled, Typography } from '@mui/material';

const FightParticipantResultTypography = styled(Typography)(({ theme }) => ({
    '&.win': {
        color: theme.palette.success.main,
    },
    '&.loss': {
        color: theme.palette.error.main,
    },
}));

export default FightParticipantResultTypography;
