const initiateFightUsingFetch: InitiateFightApi = (fighterId, onSuccess) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_FIGHT_INITIATE + fighterId, {
        method: 'POST',
        credentials: 'include',
    })
        .then(onSuccess);
};

export default initiateFightUsingFetch;
