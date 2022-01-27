Feature: It can view characters

  Scenario: it can view details for one character
    Given a client has an account
    And it owns a well advanced character
    When a client asks to read character infos
    Then details about the character were returned

  Scenario: it fails to view a character that does not exist
    When a client asks to read a character that does not exist
    Then the query was refused
