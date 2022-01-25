Feature: It counts and limits character for a single player

  Scenario: the character count is initialized on account creation
    When a client creates an account
    Then a fresh character counter is registered
