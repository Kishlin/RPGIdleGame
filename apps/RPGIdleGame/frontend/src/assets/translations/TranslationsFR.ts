const TranslationsFR: Translations = {
    components: {
        character: {
            meta: {
                rank: 'Rang: #rank#',
                skillPoints: 'Points: #skillPoints#',
                stats: 'Combats: #fights_count# (#wins_count# - #draws_count# - #losses_count#)',
                available: 'Prêt à combattre',
                resting: 'Repos jusqu\'à #date#',
            },
            links: {
                skillPoints: 'Distribuer les points',
                details: 'Détails',
                delete: 'Supprimer',
                fight: 'Combattre',
            },
        },
        fight: {
            vs: 'VS',
            draw: 'Ce combat est un match nul parce qu\'aucun combattant ne pouvait faire de dégâts à l\'autre.',
            noTurns: 'Le perdant n\'avait aucune chance de faire des dégâts au gagnant, parce que ses dégâts maximum possibles sont plus bas ou égaux à la défense du gagnant.',
            turns: {
                checkbox: {
                    allTurns: 'Montrer les tours sans dégâts causés',
                },
            },
        },
    },
    entities: {
        character: {
            health: 'Vie',
            attack: 'Attaque',
            defense: 'Défense',
            magik: 'Magie',
            resting: 'Repose jusqu\'à #date#',
        },
        fight: {
            participant: {
                headline: '#player# - #fighter#, Rang #rank#',
                health: 'Vie',
                attack: 'Attaque',
                defense: 'Défense',
                magik: 'Magie',
                headers: {
                    win: 'Victoire',
                    loss: 'Défaite',
                },
            },
            turns: {
                index: 'Tour',
                attacker: 'Attaquant',
                diceRoll: 'Dé',
                defenderDefense: 'Défense de l\'attaqué',
                damage: 'Dégâts',
                defenderHealth: 'Vie de l\'attaqué',
            },
        },
        shortFight: {
            date: 'Date',
            tooltipRank: 'Rang avant le combat',
            detailsLink: 'Détails',
            initiatorName: 'Initiateur',
            initiatorRank: 'Rang',
            opponentName: 'Opposant',
            opponentRank: 'Rang',
            result: 'Résultat',
            results: {
                win: 'Victoire',
                draw: 'Null',
                loss: 'Défaite',
            },
        },
    },
    fragments: {
        header: {
            title: 'RPGIdleGame',
            menu: {
                disconnect: 'Déconnexion',
            },
        },
    },
    pages: {
        character: {
            delete: {
                title: 'Supprimer #name# pour toujours',
                validation1: 'Voulez-vous vraiment supprimer #name# pour toujours?',
                validation2: 'Cette action nDéconnexionest pas réversible.',
                validation3: 'Le personnage sera supprimé pour toujours.',
                validation4: 'Pour. Toujours.',
                buttons: {
                    doDelete: 'Je suis sûr, supprimer pour toujours',
                    home: 'Retourner à l\'accueil en sécurité',
                },
            },
            fight: {
                title: 'Combattre avec #name#',
                explanations: {
                    main: 'Le personnage va être confronté à un opposant de niveau similaire. '
                        + 'Les combats sont du tour par tour aléatoire. '
                        + 'Chaque tour, l\'attaquant lance un dé de 1 à son niveau d\'attaque (minimum 1). '
                        + 'Si le jet de dé est égale à la magie de l\'attaquant, le résultat est multiplié par deux. '
                        + 'La défense de l\'attaqué y est soustraite avant qu\'il perde de la vie. ',
                    example: {
                        title: 'Exemple:',
                        init: '- L\'attaquant a 8 d\'attaque et 5 de magie. Le défenseur a 6 points de défense.',
                        roll: '- L\'attaquant lance un dé de 1 à 8 et obtient 5.',
                        bonus: '- Parce que c\'est égal à son niveau de magie, le résultat final est 5*2=10 plutôt que simplement 5',
                        damage: '- La défense de \'attaqué est soustraite, les dégâts finaux sont 10-6=4.',
                        final: '- Le défenseur perd 4 points de vie. S\'il est déscendu à 0, il a perdu le combat.',
                    },
                    onResult: 'Le gagnant du combat monte de un rang et gagne un point de compétence. '
                        + 'Le perdant du combat descend de un rang, mais pas plus bas que 1, et doit se reposer pendant une heure. '
                        + 'Rien ne se passe en cas de match null (personne ne peut faire de dégâts à l\'autre.',
                },
                doFight: 'Combattre',
                errors: {
                    resting: 'Ce personnage a récemment perdu un combat, il doit se reposer.',
                    noOpponent: 'Aucun opposant n\'est disponible, merci de réessayer plus tard.',
                    unknown: 'Le combat n\'a pas pu avoir lieu, merci de réessayer plus tard.',
                },
            },
            formSkillPoints: {
                title: 'Distribuer des points à #character_name#',
                helperNextSkillLevel: 'Le prochain niveau coûte #nextCost# points.',
                subtitleAvailable: 'Points disponibles: #available#.',
                subtitleCost: 'Coût total de l\'opération: #cost#.',
                warning: 'Les changements ne sont plus réversibles une fois l\'opération confirmée.',
                stats: '(Actuel: #stat#)',
                buttons: {
                    plusOne: '+1',
                    minusOne: '-1',
                    submit: 'Confirmer les changements',
                    home: 'Retour à l\'accueil',
                },
            },
            formCreate: {
                name: {
                    label: 'Nom du personnage',
                },
                buttons: {
                    submit: 'Créer un personnage',
                },
                error: 'Le personnage n\'a pas pu être créé, merci de réessayer plus tard.',
            },
            links: {
                homepage: 'Retour à l\'accueil',
            },
            view: {
                title: 'Personnage: #name#',
                rank: 'Rang: #rank#',
                score: 'Score: #wins# - #draws# - #losses#',
                creation: 'Création: #date#',
                home: 'Retour à l\'accueil',
            },
        },
        fights: {
            buttons: {
                homepage: 'Retour à l\'accueil',
            },
        },
        home: {
            anonymous: {
                title: 'Bienvenu! Il faut être connêcté pour continuer.',
                signup: 'Si tu n\'as pas de compte, tu peux en créer un.',
                login: 'Tu peux aussi directement te connecter.',
            },
            authenticated: {
                limitReached: 'Tu as atteint la limite de personnages autorisés.',
                buttons: {
                    createCharacter: 'Créer un nouveau personnage',
                },
            },
        },
        login: {
            form: {
                title: 'Bon retour!',
                login: {
                    label: 'E-mail ou nom d\'utilisateur',
                },
                password: {
                    label: 'Mot de passe',
                },
                buttons: {
                    submit: 'Me connecter',
                },
                errors: {
                    credentials: 'Ces informations ne correspondent à aucun compte existant.',
                    unknown: 'Impossible de se connecter, merci de réessayer plus tard.',
                },
            },
            links: {
                signup: 'Pas encore de compte ? M\'inscrire ici.',
            },
        },
        signup: {
            form: {
                title: 'Créer un nouveau compte',
                infos: {
                    incomplete: 'Tu dois remplir tous les champs pour t\'inscrire.',
                    complete: 'Tu es prêt !',
                },
                username: {
                    label: 'Nom d\'utilisateur',
                },
                email: {
                    label: 'E-mail',
                    error: 'Ce n\'est pas une e-mail valide.',
                },
                password: {
                    label: 'Mot de passe',
                },
                passwordCheck: {
                    label: 'Vérification du mot de passe.',
                    error: 'Les mots de passe ne correspondent pas.',
                },
                buttons: {
                    submit: 'M\'inscrire',
                },
                errors: {
                    conflict: {
                        email: 'Un autre compte utilise déjà cette e-mail.',
                        username: 'Un autre compte utilise déjà ce nom d\'utilisateur.',
                    },
                    unknown: 'Impossible de s\'inscrire, merci de réessayer plus tard.',
                },
            },
            links: {
                login: 'Déjà un compte ? Me connecter ici.',
            },
        },
    },
};

export default TranslationsFR;
