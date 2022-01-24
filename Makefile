include .env
-include .env.local

current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

CACHE=composer-cache composer node-cache node
VOL_CACHE?=$(shell docker volume inspect -f '{{ index .Mountpoint }}' cache)

# Env
.env.local:
	@touch .env.local

# 🐘 Composer
.PHONY: composer-install
composer-install: CMD=install

.PHONY: composer-update
composer-update: CMD=update

.PHONY: composer-require
composer-require: CMD=require
composer-require: INTERACTIVE=-ti --interactive

.PHONY: composer-require-module
composer-require-module: CMD=require $(module)
composer-require: INTERACTIVE=-ti --interactive

.PHONY: composer
composer-install composer-update composer-require composer-require-module: .env.local .docker-cache
	@docker-compose exec backend php -d xdebug.mode=off /usr/local/bin/composer $(CMD) \
			--working-dir=/rpgidlegame \
			--no-ansi

##> Project
docker-compose.yaml:
	@cp docker-compose.yaml.dist docker-compose.yaml
	@sed -i "s/<DOCKER_USER_ID>/$(shell $(shell echo id -u ${USER}))/g" $@
	@sed -i "s/<DOCKER_USER>/$(shell echo ${USER})/g" $@

.docker-cache:
	@touch .docker-cache
	@docker volume create --name=cache;

vendor: composer-install

node_modules:
	@docker-compose exec frontend yarn install

cache: $(CACHE)
$(CACHE): .docker-cache
	@if [ ! -d "$(VOL_CACHE)/$@" ]; then \
	sudo mkdir -pm 777 $(VOL_CACHE)/$@; \
	fi;

.INTERMEDIATE: .docker-cache

.PHONY: help setup start stop clean containers node_modules

help:
	@echo "Run make start_working"

setup: .env.local docker-compose.yaml

containers: setup $(CACHE)
	@echo "Starting services"
	@docker-compose up -d --remove-orphans

stop:
	@docker-compose down

clean:
	@if [ -f "./docker-compose.yaml" ]; then \
		docker-compose down; \
	fi;
	@sudo rm -rf docker-compose.yaml vendor

start: containers vendor node_modules db.reload
	@echo "All services should be running."
	@echo "    Backend: http://localhost:8030/monitoring/check-health"
	@echo "    Frontend: http://localhost:3000/monitoring/check-health"
	@echo "Ports may differ if overridden in the .env.local file."
	@echo "Run tests: \`make tests\` (see Makefile for more options)."

##> Helpers
.PHONY: db.connect db.reload db.migrations.diff db.migrations.migrate

db.connect:
	@docker-compose exec postgres /bin/bash -c 'psql -U $$POSTGRES_USER'

db.reload:
	@echo "Reloading database"
	@docker-compose exec postgres /bin/bash -c 'dropdb -U $$POSTGRES_USER --if-exists rpgidlegame'
	@docker-compose exec postgres /bin/bash -c 'createdb -U $$POSTGRES_USER rpgidlegame'
	@docker-compose exec postgres /bin/bash -c 'psql -q -U $$POSTGRES_USER -d rpgidlegame -f /rpgidlegame/etc/Schema/create.sql &>/dev/null'
	@echo "Done reloading database"

db.migrations.diff: CMD=diff

db.migrations.migrate: CMD=migrate

db.migrations.diff db.migrations.migrate:
	@docker-compose exec backend php -d xdebug.mode=off /rpgidlegame/vendor/bin/doctrine-migrations $(CMD)

frontend.sh:
	@docker-compose exec frontend sh

frontend.build:
	@docker-compose exec frontend yarn run build

##> Static Analysis

.PHONY: phpstan php-cs-fixer php-cs-fixer.force

phpstan:
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpstan analyse -c /rpgidlegame/phpstan.neon


php-cs-fixer: DRY_RUN="--dry-run"
php-cs-fixer php-cs-fixer.force:
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/php-cs-fixer fix --config=/rpgidlegame/.php-cs-fixer.php -vv ${DRY_RUN}

##> Tests
.PHONY: tests.backend.usecases tests.backend.src.isolated tests.backend.src.contract tests.backend.src \
		tests.backend.app.driving tests.backend.app.functional tests.backend.app.integration tests.backend.app \
		tests.backend tests.frontend tests

tests.backend.usecases:
	@echo "Running Use Case Tests for src/"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/behat --config /rpgidlegame/behat-config.yml --suite use_case_tests
	@echo ""

tests.backend.src.isolated:
	@echo "Running Isolated Tests for the src/ folder"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite isolated
	@echo ""

tests.backend.src.contract:
	@echo "Running Contract Tests for the src/ folder"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite contract
	@echo ""

tests.backend.src:
	@echo "Running Tests for the src/ folder"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml
	@echo ""

tests.backend.app.driving:
	@echo "Running Driving Tests for the Backoffice App"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/apps/RPGIdleGame/backend/phpunit.xml --testsuite driving
	@echo ""

tests.backend.app.functional:
	@echo "Running Functional Tests for the Backoffice App"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/apps/RPGIdleGame/backend/phpunit.xml --testsuite functional
	@echo ""

tests.backend.app.integration:
	@echo "Running Integration Tests for the Backoffice App"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/apps/RPGIdleGame/backend/phpunit.xml --testsuite integration
	@echo ""

tests.backend.app:
	@echo "Running Tests for the Backoffice App"
	@docker-compose exec backend php -d xdebug.mode=off \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/apps/RPGIdleGame/backend/phpunit.xml
	@echo ""

tests.frontend:
	@echo "Running Tests for the Frontend App"
	@docker-compose exec frontend yarn run test
	@echo ""


tests.backend: tests.backend.usecases tests.backend.src tests.backend.app

tests: tests.backend tests.frontend