Feature: It can authenticate

  Scenario: a client can authenticate
    Given a client has an account
    When a client authenticates with the correct credentials
    Then the authentication was authorized

  Scenario: a client cannot create an account with an email already used
    Given a client has an account
    When a client tries to authenticate with wrong credentials
    Then the authentication was refused
