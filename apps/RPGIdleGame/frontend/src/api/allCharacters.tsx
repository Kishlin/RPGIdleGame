const getAllCharactersUsingFetch: AllCharactersApi = (onCharactersListResponse, onAuthenticationFailed) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_ALL_CHARACTERS, {
        method: 'GET',
        credentials: 'include',
    }).then((response) => response.json()).then(onCharactersListResponse).catch(() => onAuthenticationFailed());
};

export default getAllCharactersUsingFetch;
