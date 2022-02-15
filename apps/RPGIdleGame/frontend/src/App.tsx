import * as React from "react";
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";

import Home from "./pages/Home";
import CheckHealth from "./pages/CheckHealth";

function App(): JSX.Element {

    return (
        <Router>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/monitoring/check-health" element={<CheckHealth />} />
                <Route path="*" element={<Navigate to="/" />} />
            </Routes>
        </Router>
    );
}

export default App;
