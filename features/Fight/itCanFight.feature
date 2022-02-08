Feature: It can fight against other characters

  Scenario: a client can make a character fight another character
    Given a client has an account
    And it owns a well advanced character
    And there is an opponent available
    When a client wants to fight with its character
    Then the fight is registered
    And the fighting stats of both participants where updated

  Scenario: a stranger cannot fight with a client's character
    Given a client has an account
    And it owns a well advanced character
    When a stranger tries to fight with the client's character
    Then the fight request was refused

  Scenario: it cannot fight if there is no opponent
    Given a client has an account
    And it owns a well advanced character
    And there is no available opponent
    When a client wants to fight with its character
    Then the fight request failed to find an opponent
