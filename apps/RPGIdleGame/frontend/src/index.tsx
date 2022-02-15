import * as React from 'react';
import * as ReactDOM from 'react-dom';

import App from './App';
import reportWebVitals from "./reportWebVitals";


ReactDOM.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>,
    document.getElementById('root')
);

if ("development" !== process.env.NODE_ENV) {
    reportWebVitals(console.log);
}

