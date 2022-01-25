Feature: On account creation

  Scenario: the account is created
    When a client creates an account
    Then a fresh character counter is registered
