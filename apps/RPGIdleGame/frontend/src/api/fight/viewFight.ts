const viewFightUsingFetch: ViewFightApi = (id, onSuccess) => {
    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_FIGHT_VIEW + id, {
        method: 'GET',
        credentials: 'include',
    })
        .then((response) => response.json())
        .then(onSuccess);
};

export default viewFightUsingFetch;
