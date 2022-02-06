Feature: It can authenticate

  Scenario: a client can authenticate
    Given a client has an account
    When a client authenticates with the correct credentials
    Then the authentication was authorized

  Scenario: a client cannot create an account with an email already used
    Given a client has an account
    When a client tries to authenticate with wrong credentials
    Then the authentication was refused

  Scenario: a client can renew its authentication
    Given a client has an account
    When a client refreshes its authentication with a valid refresh token
    Then the renewed authentication was returned

  Scenario: a client needs a valid refresh token to renew authentication
    Given a client has an account
    When a client tries to refresh with an expired refresh token
    Then renewing the authentication was refused
