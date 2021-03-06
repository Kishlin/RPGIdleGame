const resultsForParticipants = (fight: Fight): [FightResult, FightResult] => {
    if (null === fight.winner_id) {
        return ['draw', 'draw'];
    }

    if (fight.winner_id === fight.initiator.character_id) {
        return ['win', 'loss'];
    }

    return ['loss', 'win'];
};

export default resultsForParticipants;
