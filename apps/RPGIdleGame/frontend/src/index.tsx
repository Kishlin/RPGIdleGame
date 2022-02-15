import React from 'react';
import ReactDOM from 'react-dom';

import App from './App';
import reportWebVitals from './reportWebVitals';

import './index.css';

ReactDOM.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>,
    document.getElementById('root'),
);

if (process.env.NODE_ENV !== 'development') {
    // eslint-disable-next-line no-console
    reportWebVitals(console.log);
}
