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

  Scenario: skill points are distributed
    Given a client has an account
    And it owns a well advanced character
    When a client distributes some skill points
    Then the character stats are updated as wanted

  Scenario: stats updates require enough skill points
    Given a client has an account
    And it owns a well advanced character
    When a client tries to distribute more skill points than available
    Then the stats update was refused

  Scenario: the character is deleted
    Given a client has an account
    And it owns a character
    When a client deletes its character
    Then the character is deleted
    And the character count is decremented

  Scenario: it cannot delete a character it does not own
    Given a client has an account
    And it owns a character
    When a stranger tries to delete the client's character
    Then the deletion was refused
