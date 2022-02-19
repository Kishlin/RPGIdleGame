const getAllCharactersUsingFetch: AllCharactersApi = (onCharactersListResponse) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_ALL_CHARACTERS, {
        method: 'GET',
        credentials: 'include',
    })
        .then((response) => response.json())
        .then(onCharactersListResponse);
};

export default getAllCharactersUsingFetch;
