import React from 'react';
import { createTheme, CssBaseline, ThemeProvider } from '@mui/material';
import {
    BrowserRouter as Router,
    Routes,
    Route,
    Navigate,
} from 'react-router-dom';

import { LangProvider } from './context/LangContext';

import CheckHealth from './pages/CheckHealth';
import Home from './pages/Home';

function App(): JSX.Element {
    const darkTheme = createTheme({
        palette: {
            mode: 'dark',
        },
    });

    return (
        <ThemeProvider theme={darkTheme}>
            <CssBaseline />
            <LangProvider>
                <Router>
                    <Routes>
                        <Route path="/" element={<Home />} />
                        <Route path="/monitoring/check-health" element={<CheckHealth />} />
                        <Route path="*" element={<Navigate to="/" />} />
                    </Routes>
                </Router>
            </LangProvider>
        </ThemeProvider>
    );
}

export default App;
