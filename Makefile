DOCKER_EXEC_PHP_FPM = docker compose exec php-fpm

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

bash:
	$(DOCKER_EXEC_PHP_FPM) bash