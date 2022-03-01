import { styled, TableRow } from '@mui/material';

const StyledFightTurnsTableRow = styled(TableRow)(({ theme }) => ({
    '&.index-modulo-0': {
        backgroundColor: theme.palette.action.hover,
    },
    // hide last border
    '&:last-child td, &:last-child th': {
        border: 0,
    },
}));

export default StyledFightTurnsTableRow;
