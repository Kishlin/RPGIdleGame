Feature: Account creation

  Scenario: the account is created
    When a client creates an account
    Then its credentials are registered
    And a fresh character count is registered

  Scenario: two account can't use the same email
    Given an account already exists with the email
    When a client creates an account with the same email
    Then it did not register the new account
