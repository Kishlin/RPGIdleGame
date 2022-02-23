const allFightsOfFighterApi: AllFightsOfFighterApi = (fighterId, onSuccess) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_FIGHTS + fighterId, {
        method: 'GET',
        credentials: 'include',
    })
        .then((response) => response.json())
        .then(onSuccess);
};

export default allFightsOfFighterApi;
