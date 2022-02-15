import * as React from "react";

import {LangContext} from "../context/LangContext";
import ChangeLangButton from "../components/i18n/ChangeLangButton";

const Home = (): JSX.Element => {
    const { t } = React.useContext<LangContextType>(LangContext);

    return (
        <>
            <ChangeLangButton />
            <p>{ t('fragments.header.title') }</p>
        </>
    )
};

export default Home;
