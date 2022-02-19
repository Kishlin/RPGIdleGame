const TranslationsEN: Translations = {
    components: {
        character: {
            links: {
                skillPoints: 'Distribute skill points',
                delete: 'Delete',
                fight: 'Fight',
            },
        },
    },
    entities: {
        character: {
            health: 'Health',
            attack: 'Attack',
            defense: 'Defense',
            magik: 'Magik',
        },
    },
    fragments: {
        header: {
            title: 'RPGIdleGame',
            menu: {
                disconnect: 'Disconnect',
            },
        },
    },
    pages: {
        character: {
            delete: {
                title: 'Delete #name# forever',
                validation1: 'Are you sure you want to delete #name#?',
                validation2: 'This action cannot be reversed.',
                validation3: 'The character will be gone forever.',
                validation4: 'For. Ever.',
                buttons: {
                    doDelete: 'Yes. Delete forever',
                    home: 'Safely go back to home',
                },
            },
            formSkillPoints: {
                title: 'Distribute skill points to #character_name#',
                helperNextSkillLevel: 'Next level will cost #nextCost# points.',
                subtitleAvailable: 'Available: #available#.',
                subtitleCost: 'Current operation\'s cost: #cost#.',
                warning: 'Changes will not be reversible after the operation is confirmed.',
                stats: '(Current: #stat#)',
                buttons: {
                    plusOne: '+1',
                    minusOne: '-1',
                    submit: 'Confirm operation',
                    home: 'Cancel',
                },
            },
            formCreate: {
                name: {
                    label: 'Character name',
                },
                buttons: {
                    submit: 'Create character',
                },
                error: 'An unexpected error occurred. Please try again later.',
            },
            links: {
                homepage: 'Back to home',
            },
        },
        home: {
            anonymous: {
                title: 'Welcome! You need an account to continue.',
                signup: 'If you do not have an account yet, you can sign up.',
                login: 'Otherwise, you can directly log in.',
            },
            authenticated: {
                buttons: {
                    createCharacter: 'Create a new Character',
                },
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
