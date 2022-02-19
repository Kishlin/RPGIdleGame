const refreshAuthenticationUsingFetch: RefreshAuthenticationApi = (onRefreshSuccess, onRefreshFailure) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_REFRESH_AUTHENTICATION, {
        method: 'POST',
        credentials: 'include',
    })
        .then(onRefreshSuccess)
        .catch(onRefreshFailure);
};

export default refreshAuthenticationUsingFetch;
