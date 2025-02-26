DOCKER_COMPOSE = docker compose
DOCKER_EXEC = $(DOCKER_COMPOSE) exec
DOCKER_EXEC_PHP_FPM = $(DOCKER_EXEC) php-fpm

.PHONE: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: index
index:
	echo "Indexing conferences"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-conferences --reset --env=prod --no-debug
	echo "Indexing editions"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-conference-editions --reset --env=prod --no-debug
	echo "Indexing speakers"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-speakers --reset --env=prod --no-debug
	echo "Indexing talks"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-talks --reset --env=prod --no-debug

.PHONY: bash
bash:
	$(DOCKER_EXEC_PHP_FPM) bash

.PHONY: composer-install
composer-install:
	$(DOCKER_EXEC_PHP_FPM) composer install

.PHONE: fixtures
fixtures:
	@echo "This will drop the database and reload the fixtures. Are you sure? [y/N]" && read -r response && [ $$response = "y" ]
	$(DOCKER_EXEC_PHP_FPM) php bin/console doctrine:fixtures:load --no-interaction

.PHONY: tests
tests:
	$(DOCKER_EXEC_PHP_FPM) bash .github/init-test.sh
	$(DOCKER_EXEC_PHP_FPM) php bin/phpunit

.PHONY: composer-update
composer-update:
	$(DOCKER_EXEC_PHP_FPM) composer update

.PHONY: phpstan
phpstan:
	$(DOCKER_EXEC_PHP_FPM) php vendor/bin/phpstan analyse src

.PHONY: phpcsfixer
phpcsfixer:
	$(DOCKER_EXEC) -e PHP_CS_FIXER_IGNORE_ENV=1 php-fpm php vendor/bin/php-cs-fixer fix src

.PHONY: rector
rector:
	$(DOCKER_EXEC) -e RECTOR=1 php-fpm php vendor/bin/rector process src