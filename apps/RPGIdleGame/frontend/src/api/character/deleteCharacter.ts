const deleteCharacterUsingFetch: DeleteCharactersApi = (id, onSuccess) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_CHARACTER + id, {
        method: 'DELETE',
        credentials: 'include',
    })
        .then(onSuccess);
};

export default deleteCharacterUsingFetch;
