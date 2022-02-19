const updateCharacterUsingFetch: UpdateCharactersApi = (id, params, onSuccess) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_CHARACTER + id, {
        method: 'PUT',
        credentials: 'include',
        body: JSON.stringify(params),
    })
        .then((response) => response.json())
        .then(onSuccess);
};

export default updateCharacterUsingFetch;
