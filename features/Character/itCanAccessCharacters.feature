Feature: It can view characters

  Scenario: a client can view details for one of its character
    Given a client has an account
    And it owns a well advanced character
    When a client asks to read one of its character's infos
    Then details about the character were returned

  Scenario: a client cannot view details of a character that does not exist
    When a client asks to read a character that does not exist
    Then the query was refused

  Scenario: a stranger cannot access the details of a client's character
    Given a client has an account
    And it owns a character
    When a stranger tries to read its character
    Then the query was refused