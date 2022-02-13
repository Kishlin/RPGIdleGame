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
	@docker-compose exec backend php /usr/local/bin/composer $(CMD) \
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
	@docker-compose exec frontend npm install

cache: $(CACHE)
$(CACHE): .docker-cache
	@if [ ! -d "$(VOL_CACHE)/$@" ]; then \
	sudo mkdir -pm 777 $(VOL_CACHE)/$@; \
	fi;

.INTERMEDIATE: .docker-cache

git-hooks:
	@cp .git-hooks/commit-msg.sh .git/hooks/commit-msg
	@cp .git-hooks/pre-commit.sh .git/hooks/pre-commit

.PHONY: help setup start stop clean containers node_modules git-hooks

help:
	@echo "Run make start_working"

setup: .env.local docker-compose.yaml git-hooks

containers: setup $(CACHE)
	@echo "Starting services"
	@docker-compose up -d --remove-orphans

stop:
	@docker-compose down

clean:
	@if [ -f "./docker-compose.yaml" ]; then \
		docker-compose down; \
	fi;
	@sudo rm -rf docker-compose.yaml vendor apps/RPGIdleGame/frontend/node_modules apps/RPGIdleGame/frontend/build
	@sudo rm -rf .git/hooks/commit-msg .git/hooks/pre-commit

start: containers vendor db.reload db.reload.test
	@echo "All services should be running."
	@echo "    Backend: http://localhost:8030/monitoring/check-health"
	@echo "    Frontend: http://localhost:3000/monitoring/check-health"
	@echo "Ports may differ if overridden in the .env.local file."
	@echo "Run tests: \`make tests\` (see Makefile for more options)."

##> Helpers
.PHONY: xdebug.on xdebug.off
.PHONY: db.connect db.reload db.reload.test db.migrations.diff db.migrations.migrate frontend.sh frontend.build

xdebug.on:
	@docker-compose exec backend sudo mv /usr/local/etc/php/conf.d/xdebug.ini.dis /usr/local/etc/php/conf.d/xdebug.ini

xdebug.off:
	@docker-compose exec backend sudo mv /usr/local/etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.dis

db.reload: ENV=dev
db.reload.test: ENV=test

db.reload db.reload.test:
	@echo "Creating $(ENV) database"
	@docker-compose exec postgres /bin/bash -c 'dropdb -U $$POSTGRES_USER --if-exists rpgidlegame-$(ENV) &>/dev/null'
	@docker-compose exec postgres /bin/bash -c 'createdb -U $$POSTGRES_USER rpgidlegame-$(ENV)'
	@docker-compose exec postgres /bin/bash -c 'psql -q -U $$POSTGRES_USER -d rpgidlegame-$(ENV) -f /rpgidlegame/etc/Schema/create.sql &>/dev/null'
	@echo "Done reloading $(ENV) database"

db.connect:
	@docker-compose exec postgres /bin/bash -c 'psql -U $$POSTGRES_USER -d rpgidlegame-dev'

db.dump:
	@echo "Dump DB schema to file"
	@docker-compose exec postgres /bin/bash -c 'pg_dump -U $$POSTGRES_USER -d rpgidlegame-dev > /rpgidlegame/etc/Schema/create.sql'

db.migrations.diff: CMD=diff

db.migrations.migrate: CMD=migrate

db.migrations.diff db.migrations.migrate:
	@docker-compose exec backend php /rpgidlegame/vendor/bin/doctrine-migrations $(CMD)

frontend.sh:
	@docker-compose exec frontend sh

frontend.build:
	@docker-compose exec frontend yarn run build

##> Tests
.PHONY: tests.backend.usecases test.backend.api tests.backend.src.isolated tests.backend.src.contract tests.backend.src \
		tests.backend.app.driving tests.backend.app.functional tests.backend.app.integration tests.backend.app \
		tests.backend tests.frontend tests

tests.backend.usecases:
	@echo "Running Use Case Tests for src/"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/behat --config /rpgidlegame/behat-config.yml --suite use_case_tests
	@echo ""

tests.backend.api:
	@echo "Running API Tests for RPGIdleGame Backend app"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/behat --config /rpgidlegame/behat-config.yml --suite api_tests
	@echo ""

tests.backend.src.isolated:
	@echo "Running Isolated Tests for the src/ folder"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite isolated
	@echo ""

tests.backend.src.contract:
	@echo "Running Contract Tests for the src/ folder"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite contract
	@echo ""

tests.backend.src:
	@echo "Running Tests for the src/ folder"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite isolated,contract
	@echo ""

tests.backend.app.driving:
	@echo "Running Driving Tests for the Backend App"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite driving
	@echo ""

tests.backend.app.functional:
	@echo "Running Functional Tests for the Backend App"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite functional
	@echo ""

tests.backend.app.integration:
	@echo "Running Integration Tests for the Backend App"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite integration
	@echo ""

tests.backend.app:
	@echo "Running Tests for the Backend App"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpunit -c /rpgidlegame/phpunit.xml --testsuite driving,functional,integration
	@echo ""

tests.frontend:
	@echo "Running Tests for the Frontend App"
	@docker-compose exec frontend yarn run test
	@echo ""


tests.backend: tests.backend.usecases tests.backend.api tests.backend.src tests.backend.app

tests: tests.backend tests.frontend

##> Static Analysis

.PHONY: phpstan php-cs-fixer php-cs-fixer.force complete-analysis

phpstan:
	@echo "Running PHPStan"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/phpstan analyse -c /rpgidlegame/phpstan.neon
	@echo ""


php-cs-fixer: DRY_RUN="--dry-run"
php-cs-fixer php-cs-fixer.force:
	@echo "Running PHP-Cs-Fixer ${DRY_RUN}"
	@docker-compose exec backend php \
		/rpgidlegame/vendor/bin/php-cs-fixer fix --config=/rpgidlegame/.php-cs-fixer.php -vv ${DRY_RUN}
	@echo ""

complete-analysis: tests phpstan php-cs-fixer
