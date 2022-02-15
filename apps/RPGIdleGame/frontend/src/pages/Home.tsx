import React, { useContext } from 'react';

import { LangContext } from '../context/LangContext';
import ChangeLangButton from '../components/i18n/ChangeLangButton';

function Home(): JSX.Element {
    const { t } = useContext<LangContextType>(LangContext);

    return (
        <>
            <ChangeLangButton />
            <p>{ t('fragments.header.title') }</p>
        </>
    );
}

export default Home;
