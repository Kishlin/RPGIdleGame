Feature: It can create, update, and delete characters

  Scenario: the character is created
    Given a client has an account
    When a client creates a character
    Then the character is registered
    And the character count is incremented

  Scenario: it cannot create too many characters
    Given a client has an account
    And it has reached the character limit
    When a client creates a character
    Then the creation was refused