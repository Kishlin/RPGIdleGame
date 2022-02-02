Feature: It can view fights

  Scenario: a client can view details for a fight one of its character was part of
    Given a client has an account
    And it owns a well advanced character
    And there is an opponent available
    And its character takes part in a fight with the opponent
    When a client asks to read one the fight's infos
    Then details about the fight were returned

  Scenario: a client cannot view details of a fight that does not exist
    When a client asks to read a fight that does not exist
    Then the query for the fight infos was refused

  Scenario: a stranger cannot access the details of a fight none of his character were a part of
    Given a client has an account
    And it owns a well advanced character
    And there is an opponent available
    And its character takes part in a fight with the opponent
    When a stranger tries to read the fight's infos
    Then the query for the fight infos was refused
