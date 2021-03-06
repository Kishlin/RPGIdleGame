const TranslationsEN: Translations = {
    components: {
        character: {
            meta: {
                rank: 'Rank: #rank#',
                skillPoints: 'Skill Points: #skillPoints#',
                stats: 'Fights: #fights_count# (#wins_count# - #draws_count# - #losses_count#)',
                available: 'Ready to fight',
                resting: 'Resting until #date#',
            },
            links: {
                skillPoints: 'Distribute skill points',
                details: 'Details',
                delete: 'Delete',
                fight: 'Fight',
            },
        },
        fight: {
            vs: 'VS',
            draw: 'This fight was a draw because nobody had any chance to damage the other.',
            noTurns: 'The loser had no chance to damage the winner, because its maximum damage output is not greater than the winner\'s defense.',
            turns: {
                checkbox: {
                    allTurns: 'Show turns with 0 damages dealt',
                },
            },
        },
    },
    entities: {
        character: {
            health: 'Health',
            attack: 'Attack',
            defense: 'Defense',
            magik: 'Magik',
            resting: 'Resting until #date#',
        },
        fight: {
            participant: {
                headline: '#player# - #fighter#, Rank #rank#',
                health: 'Health',
                attack: 'Attack',
                defense: 'Defense',
                magik: 'Magik',
                headers: {
                    win: 'Victory',
                    loss: 'Defeat',
                },
            },
            turns: {
                index: 'Turn',
                attacker: 'Attacker',
                diceRoll: 'Dice Roll',
                defenderDefense: 'Defender Defense',
                damage: 'Damage',
                defenderHealth: 'Defender Health',
            },
        },
        shortFight: {
            date: 'Date',
            tooltipRank: 'Rank before the fight',
            detailsLink: 'Details',
            initiatorName: 'Initiator',
            initiatorRank: 'Rank',
            opponentName: 'Opponent',
            opponentRank: 'Rank',
            result: 'Result',
            results: {
                win: 'Victory',
                draw: 'Draw',
                loss: 'Defeat',
            },
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
                    doDelete: 'I\'m sure, delete forever',
                    home: 'Safely go back to home',
                },
            },
            fight: {
                title: 'Fight with #name#',
                explanations: {
                    main: 'You will be matched against an opponent close to your own level. '
                        + 'Fights are random and turn-based. '
                        + 'Each turn, the attacker rolls a dice from 1 to its attack stat (minimum 1). '
                        + 'If the roll equals the magik stat, it is multiplied by two. '
                        + 'The defender\'s defense is then subtracted before it loses health points. ',
                    example: {
                        title: 'Example:',
                        init: '- The attacker has 8 attack points and 5 magik points. The defender has 6 defense points.',
                        roll: '- The fighter rolls a dice from 1 to 8 and gets a 5.',
                        bonus: '- Because it equals its magik stat, final attack is 5*2=10 instead of just 5.',
                        damage: '- The defender\'s defense is subtracted, final damages are 10-6=4.',
                        final: '- The defender loses 4 health points. If it is down to 0, the defender has lost.',
                    },
                    onResult: 'If you win, you gain one skill points and rank up by one. '
                        + 'If you lose, you rank down, but not lower than Rank 1, and must rest for one hour. '
                        + 'Nothing happens if it is a draw (fighters cannot damage each other).',
                },
                doFight: 'Fight now',
                errors: {
                    resting: 'This character recently lost a fight and is resting, come back later.',
                    noOpponent: 'No opponent available, please try again later.',
                    unknown: 'Failed to fight, please try again later.',
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
            view: {
                title: 'Character: #name#',
                rank: 'Rank: #rank#',
                score: 'Score: #wins# - #draws# - #losses#',
                creation: 'Creation: #date#',
                home: 'Back to Home',
            },
        },
        fights: {
            buttons: {
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
                limitReached: 'You have reached the limit of characters allowed.',
                buttons: {
                    createCharacter: 'Create a new Character',
                },
            },
        },
        login: {
            form: {
                title: 'Welcome back!',
                login: {
                    label: 'Email or username',
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
                    conflict: {
                        email: 'This email is already used.',
                        username: 'This username is already used.',
                    },
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
