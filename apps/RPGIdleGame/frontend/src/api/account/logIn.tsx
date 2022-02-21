import base64 from 'base-64';

const logInUsingFetch: LogInApi = ({ email, password }, onLogInResponse) => {
    const authorization = base64.encode(`${email}:${password}`);

    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_LOG_IN, {
        method: 'POST',
        credentials: 'include',
        headers: new Headers({
            Authorization: `Basic ${authorization}`,
        }),
    }).then(onLogInResponse);
};

export default logInUsingFetch;
