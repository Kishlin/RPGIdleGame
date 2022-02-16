import base64 from 'base-64';

const signUpUsingFetch: SignUpApi = ({ email, username, password }, onSignUpResponse) => {
    const authorization = base64.encode(`${username}:${password}`);

    const request = {
        method: 'POST',
        headers: new Headers({
            Authorization: `Basic ${authorization}`,
            'Content-Type': 'application/json',
        }),
        body: JSON.stringify({ email }),
    };

    fetch(process.env.REACT_APP_API_HOST + process.env.REACT_APP_API_SIGN_UP, request).then(onSignUpResponse);
};

export default signUpUsingFetch;
