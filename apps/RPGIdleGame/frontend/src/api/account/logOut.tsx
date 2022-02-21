const logOutUsingFetch: LogOutApi = (onLoggingOutSuccess) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_LOG_OUT, {
        method: 'POST',
        credentials: 'include',
    }).then(onLoggingOutSuccess);
};

export default logOutUsingFetch;
