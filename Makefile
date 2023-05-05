DOCKER_EXEC_PHP_FPM = docker exec -it dev-conferences-php-fpm

.PHONY: index
index:
	echo "Indexing conferences"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-conferences --no-debug
	echo "Indexing editions"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-conference-editions --no-debug
	echo "Indexing speakers"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-speakers --no-debug
	echo "Indexing talks"
	$(DOCKER_EXEC_PHP_FPM) php -d memory_limit=-1 bin/console app:search:index-talks --no-debug

