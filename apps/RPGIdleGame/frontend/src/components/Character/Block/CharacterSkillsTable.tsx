import { Table, TableBody, TableContainer } from '@mui/material';
import { tableCellClasses } from '@mui/material/TableCell';
import React, { useContext } from 'react';

import { LangContext } from '../../../context/LangContext';

import CharacterSkillsTableRow from './CharacterSkillsTableRow';

const skills = ['health', 'attack', 'defense', 'magik'] as const;
declare type characterSkillType = typeof skills[number];

const tableStyleOverride = {
    [`& .${tableCellClasses.root}`]: {
        borderBottom: 'none',
    },
};

function CharacterSkillsTable({ characterSkills }: CharacterSkillsTableProps): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    const tableRows = skills.map((skill: characterSkillType) => (
        <CharacterSkillsTableRow key={skill} label={t(`entities.character.${skill}`)} value={characterSkills[skill]} />
    ));

    return (
        <TableContainer sx={{ maxWidth: '250px' }}>
            <Table size="small" sx={tableStyleOverride}>
                <TableBody>{tableRows}</TableBody>
            </Table>
        </TableContainer>
    );
}

export default CharacterSkillsTable;
