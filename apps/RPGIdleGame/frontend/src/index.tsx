import React from 'react';
import ReactDOM from 'react-dom';
import { createTheme, CssBaseline, ThemeProvider } from '@mui/material';

import reportWebVitals from './reportWebVitals';

import { LangProvider } from './context/LangContext';
import { UserProvider } from './context/UserContext';

import App from './App';

import './index.css';

const darkTheme = createTheme({
    palette: {
        mode: 'dark',
    },
});

ReactDOM.render(
    <React.StrictMode>
        <ThemeProvider theme={darkTheme}>
            <CssBaseline />
            <LangProvider>
                <UserProvider>
                    <App />
                </UserProvider>
            </LangProvider>
        </ThemeProvider>
    </React.StrictMode>,
    document.getElementById('root'),
);

if ('development' !== process.env.NODE_ENV) {
    // eslint-disable-next-line no-console
    reportWebVitals(console.log);
}
