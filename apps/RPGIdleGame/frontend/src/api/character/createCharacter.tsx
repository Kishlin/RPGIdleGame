const createCharacterUsingFetch: CreateCharacterApi = ({ name }, onCreateCharacterResponse, onCreationFailure) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_CREATE_CHARACTER, {
        method: 'POST',
        credentials: 'include',
        body: JSON.stringify({ characterName: name }),
    })
        .then((response) => response.json())
        .then(onCreateCharacterResponse)
        .catch(onCreationFailure);
};

export default createCharacterUsingFetch;
