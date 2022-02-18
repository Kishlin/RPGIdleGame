const TranslationsEN: Translations = {
    fragments: {
        header: {
            title: 'RPGIdleGame',
            menu: {
                disconnect: 'Disconnect',
            },
        },
    },
    pages: {
        home: {
            anonymous: {
                title: 'Welcome! You need an account to continue.',
                signup: 'If you do not have an account yet, you can sign up.',
                login: 'Otherwise, you can log in directly.',
            },
        },
        login: {
            form: {
                title: 'Welcome back!',
                login: {
                    label: 'Email',
                },
                password: {
                    label: 'Password',
                },
                buttons: {
                    submit: 'Log In',
                },
                errors: {
                    credentials: 'The credentials do not match any existing account.',
                    unknown: 'An unexpected error occurred, please try again later.',
                },
            },
            links: {
                signup: 'No account yet? Create a new one instead.',
            },
        },
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
            links: {
                login: 'Already have an account? Log in instead.',
            },
        },
    },
};

export default TranslationsEN;
