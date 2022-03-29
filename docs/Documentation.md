# RPGIdleGameCreator

[![Symfony](https://img.shields.io/badge/Symfony-v6.0-blue.svg)](https://Symfony.com/)
[![React](https://img.shields.io/badge/React-v17.0-orange.svg)](https://symfony.com/)
[![Docker](https://img.shields.io/badge/Docker-v20.10-yellowgreen.svg)](https://Symfony.com/)
[![Docker-Compose](https://img.shields.io/badge/DockerCompose-v1.29-blueviolet.svg)](https://symfony.com/)

[![licence](https://img.shields.io/badge/licence-MIT-green.svg)](https://github.com/Kishlin/RPGIdleGame/blob/master/LICENSE.md)

## Development insights

Three applications are packaged:
- The Backend API uses Symfony and offers endpoints to interact with resources.
- The Frontend App is a Single Page Application which uses React. It communicates with the Backend API through authentication/refresh JWTs.
- A Backoffice App uses Symfony and Twig to give admins an overview of the data (players, characters, fights). It also offers commands to add data manually if need be.

The project is developed under approaches including TDD and DDD (as introduced by Mathias Verraes, Matthias Noback, Robert Martin, ...). Main concerns were to deliver a code that is self explanatory, easy to read and maintain, highly testable and tested to be trusted and refactorable. All to ensure the apps are quick and cheap to maintain and evolve on long terms.

Docker and Docker Compose are used to run project-owned containers, based on [homemade images](https://github.com/Kishlin/docker-images).

Static Analysis tools include GrumPHP, PHPStan, PHPCsFixer, ESLint, and tests with PHPUnit, Behat, and Jest.

## Project Tree

```
RPGIdleGame
└─── .docker - Dockerfiles and files required to setup docker containers.
└─── .git-hooks - Git hooks running GrumPHP tasks.
|
└─── apps
|    └─── Backoffice - The backoffice app for admins.
|    └─── RPGIdleGame
|         └─── backend - The backend API app, used by the game.
|         └─── frontend - The front app for players.
|
└─── etc - Setup files (Database details, dump files, configuration files like crontab, deploy scripts, etc).
|
└─── features - Gherkin files with the main project's features.
|
└─── src - Isolated sources, meant to be used by clients (controllers, commands, etc).
|    └─── Backend
|         └─── {Module} - Any usable module, developed with a DDD approach.
|              └─── Application - Module's use cases. 
|              |    └─── {Use Case} 
|              |         └─── {Command or Query DTO}
|              |         └─── {Command or Query Handler}
|              |         └─── {Domain Event Subscriber}
|              |         └─── {Ports, Exceptions}
|              └─── Domain - Isolated domain with port interfaces.
|              |    └─── ReadModel - Objects for retrieving informations for an internal use. 
|              |    └─── ValueObject - ValueObjects used by entities.
|              |    └─── View - Objects for retrieving informations for an external use by clients.
|              |    |    {Domain Entities}
|              |    |    {Domain Events}
|              |    |    {Domain Exceptions}
|              |    |    {Ports}
|              └─── Infrastructure - Outgoing ports adapters
|                   └─── {Outgoing ports adapaters (repositories implementations, ...}
|                   └─── {Configuration files}
|                   |    ...
|
└─── tests - Tests for isolated sources.
|
|    ...
 ```

Notes: The frontend app was created using create-react-app and does not allow importing source files from outside the app's directory. 
This makes it harder to externalize the app's sources to the root src folder alongside Backend sources.
A workaround would be to use the local packages functions of NPM but this breaks hot reloading in dev environment.


## Test suites

Tests were written using a TDD approach to help design production code. 
Tests are designed to be as fast and efficient as possible.

Infrastructure Tests use a dedicated test database rather than the development database.
However, Acceptance Test for the API use the actual dev API running and therefore wipe out and use the dev database.
This means running API tests will erase any data stored in the dev database.

### Sources' tests

- Contract Tests are designed to test outgoing port adapters which require an infrastructure running (database).
- Isolated Tests are unit tests for the domain classes, and Infrastructure services which can be tested in isolation (using test doubles instead of an actual infrastructure). 
- Use Case Tests are acceptance tests based on the Gherkin features files and dedicated to application services. They use a dedicated service container, and test spies instead of actual outgoing adapters, to ensure tests do not depend on any infrastructure.

### Apps' tests

- Api Tests are acceptance tests based on the Gherkin features files. They require a complete infrastructure, and simulates actual HTTP requests using a real HTTP client. They require the app to be running which will use the DEVELOPMENT database.
- Driving Tests are integration tests dedicated to the incoming adapters. They make sure adapters are using the Application Services the right way.
- Functional Tests are functional tests for clients (controllers, commands) which do not interact with application services.
- Integration tests ensure the interconnection of services.


## What's next?

- Update the frontend api client to use the refresh token when the authentication token has expired. This is not causing issues at the moment only because authentication tokens do not expire yet.
- Frontend tests, consider using Jasmine instead of Jest and/or compiling a test environment to run the code in.
- Ensure that, when running Api Tests, the app uses the test database instead of the development database.
- More security features like validating emails, allowing password resets and password changes, backoffice task to invalidate a refresh token.
- Improved security on inputs (authorized characters in account usernames and character names, maybe case-insensitive account username duplicates).
- Features: Leaderboard, more statistics about characters and fights, more monitoring.
- Add analysis tools like Blackfire, NewRelic, SensioLabs Insights.
- Setup the PHPUnit reporting tool to get more insights about test coverage and code complexity.
- Some refactoring on complex classes like Character and Fight Domain Entities, which probably have too much cyclomatic complexity.
- Backoffice is quick and dirty for the sake of showing an additional application. It needs security, refactoring, enhancements, more features.
