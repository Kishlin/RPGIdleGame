# RPGIdleGameCreator

[![Symfony](https://img.shields.io/badge/Symfony-v6.0-blue.svg)](https://Symfony.com/)
[![React](https://img.shields.io/badge/React-v17.0-orange.svg)](https://symfony.com/)
[![Docker](https://img.shields.io/badge/Docker-v20.10-yellowgreen.svg)](https://Symfony.com/) 
[![Docker-Compose](https://img.shields.io/badge/DockerCompose-v1.29-blueviolet.svg)](https://symfony.com/)

[![licence](https://img.shields.io/badge/licence-MIT-green.svg)](https://github.com/Kishlin/RPGIdleGame/blob/master/LICENSE.md)

## Download locally

```shell
git clone git@github.com:Kishlin/RPGIdleGame.git
cd RPGIdleGame
```

## Run the project in dev environment

```shell
make start
```

Tested with Docker version 20.10.12 & docker-compose version 1.29.2.

For further details about what happens, and alternative installation processes, see Alternative Installation.

This command is always enough. 
On first start, or if you remove required folders (like dependencies), everything will be rebuilt automatically.

## Links

| Service      | RPGIdleGame Backend                                           | RPGIdleGame Frontend                                          | Backoffice                                                    |
|--------------|---------------------------------------------------------------|---------------------------------------------------------------|---------------------------------------------------------------|
| Check Health | [Check Health](http://localhost:8030/monitoring/check-health) | [Check Health](http://localhost:3000/monitoring/check-health) | [Check Health](http://localhost:8040/monitoring/check-health) |
| Home Page    |                                                               | [Home Page](http://localhost:3000/)                           |                                                               |

Ports may differ if overridden in the .env.local file.

## Running tests

```shell
make tests
```
This requires containers to be running, containers will be started automatically if they are down.

You can be more precise and run tests for a specific app:
```shell
make tests.frontend && make tests.backend
```
Or specific test suites:
```shell
make tests.backend.usecases && make tests.backend.src && make tests.backend.app
make tests.backend.app.driving && make tests.backend.app.functional && make make tests.backend.app.integration 
make tests.backend.src.isolated && make tests.backend.src.contract
```

Tests use a specific database, created on startup.


## Alternative Installation

The `make start` command will:
1. Create a `docker-compose.yaml` from the dist file, using your shell user.
2. Create the docker cache using sudo. You'll have to input your sudo password. Otherwise, check alternatives below.
3. Build the containers. See `docker-compose.yaml` and the `.docker/` folder for details.
4. Install Composer and Node dependencies.
5. Start the Symfony and React server for dev environment.
6. Reload the dev and test databases with the dump schema file from `etc/Schema/create.sql`

Alternatively, you can install the project step by step:
1. Copy `docker-compose.yaml.dist` to `docker-compose.yaml` : `cp docker-compose.yaml.dist docker-compose.yaml`
2. Replace the `<DOCKER_USER>` and `<DOCKER_ID>` vars in `docker-compose.yaml`.
3. Create a docker volume named cache (or otherwise, but edit the `docker-compose.yaml` accordingly).
4. (Optional) The `docker-compose.yaml` file isn't versioned. Modify it to your own needs.
5. (Optional) Override the `.env` variables in a `.env.local` file, also not versioned.
6. Up the containers `docker-compose up -d`. Frontend dependencies will be installed then, before the servers are started.
7. `make vendor` and install backend dependencies.
8. `make db.reload` will create the database schema from the dump file in `etc/Schema/create.sql`
9. `make db.reload.test` will create the specific database for tests.
10. You're all set! 

From there, you can:

Base docker images come from Kishlin's docker-hub and were created from [Kishlin/docker-images](https://github.com/Kishlin/docker-images).
