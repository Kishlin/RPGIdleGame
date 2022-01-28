Feature: It can create, update, and delete characters

  Scenario: a client can create a character
    Given a client has an account
    When a client creates a character
    Then the character is registered
    And the character count is incremented

  Scenario: a client cannot create too many characters
    Given a client has an account
    And it has reached the character limit
    When a client creates a character
    Then the creation was refused

  Scenario: a client can distribute skill points on one of its character
    Given a client has an account
    And it owns a well advanced character
    When a client distributes some skill points
    Then the character stats are updated as wanted

  Scenario: a client cannot distribute more skill points than available
    Given a client has an account
    And it owns a well advanced character
    When a client tries to distribute more skill points than available
    Then the stats update was refused

  Scenario: a stranger cannot distribute skill points for one of a client's character
    Given a client has an account
    And it owns a character
    When a stranger tries to distribute skill points to its character
    Then the stats update was denied

  Scenario: a client can delete one of its character
    Given a client has an account
    And it owns a character
    When a client deletes its character
    Then the character is deleted
    And the character count is decremented

  Scenario: a stranger cannot delete one of a client's character
    Given a client has an account
    And it owns a character
    When a stranger tries to delete the client's character
    Then the deletion was refused
