const TranslationsEN: Translations = {
    fragments: {
        header: {
            title: 'RPGIdleGame',
        },
    },
    pages: {
        signup: {
            form: {
                title: 'Create a new account',
                infos: {
                    incomplete: 'You must fill each fields before you can sign up.',
                    complete: 'You\'re all set!',
                },
                username: {
                    label: 'Username',
                },
                email: {
                    label: 'Email',
                    error: 'This is not a valid email.',
                },
                password: {
                    label: 'Password',
                },
                passwordCheck: {
                    label: 'Re-enter your password',
                    error: 'The passwords do not match.',
                },
                buttons: {
                    submit: 'Sign Up',
                },
                errors: {
                    conflict: 'Email already in use.',
                    unknown: 'An unexpected error occurred, please try again later.',
                },
            },
        },
    },
};

export default TranslationsEN;
