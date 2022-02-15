import * as React from "react";
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";

import Home from "./pages/Home";
import CheckHealth from "./pages/CheckHealth";
import {LangProvider} from "./context/LangContext";

function App(): JSX.Element {

    return (
        <LangProvider>
            <Router>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/monitoring/check-health" element={<CheckHealth />} />
                    <Route path="*" element={<Navigate to="/" />} />
                </Routes>
            </Router>
        </LangProvider>
    );
}

export default App;
