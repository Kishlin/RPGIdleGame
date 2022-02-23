Feature: Account creation

  Scenario: a client creates an account
    When a client creates an account
    Then its credentials are registered
    And a fresh character count is registered

  Scenario: a client cannot create an account with an email already used
    Given an account already exists with the email
    When a client creates an account with the same email
    Then it did not register the new account

  Scenario: a client cannot create an account with a username already used
    Given an account already exists with the username
    When a client creates an account with the same username
    Then it did not register the new account
