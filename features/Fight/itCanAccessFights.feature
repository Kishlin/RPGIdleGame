Feature: It can view fights

  Scenario: a client can view details for a fight one of its character was part of
    Given a client has an account
    And it owns a well advanced character
    And there is an opponent available
    And its character took part in a fight with the opponent
    When a client asks to view one the fight's infos
    Then details about the fight were returned

  Scenario: a client cannot view details of a fight that does not exist
    When a client asks to view a fight that does not exist
    Then the query for the fight infos was refused

  Scenario: a stranger cannot access the details of a fight none of his character were a part of
    Given a client has an account
    And it owns a well advanced character
    And there is an opponent available
    And its character took part in a fight with the opponent
    When a stranger tries to view the fight's infos
    Then the query for the fight infos was refused

  Scenario: a client can view all the fights of one of its character
    Given a client has an account
    And it owns a well advanced character
    And its character took part in a few fights
    When a client asks to view the fights of its character
    Then details about all the fights were returned

  Scenario: a client can gets an empty response when its character did not take part in any fight
    Given a client has an account
    And it owns a well advanced character
    And its character did not take part in any fights
    When a client asks to view the fights of its character
    Then it gets a response with an empty fight list

  Scenario: a stranger cannot view fights of a client's character
    Given a client has an account
    And it owns a well advanced character
    When a stranger asks to view the fights of a client's character
    Then the query for all the fights was refused
