#!/bin/sh
set -e

cd /application

mkdir -p var/cache var/log
chown -R www-data:www-data var

php bin/console cache:clear --env=prod --no-debug --no-warmup
php bin/console cache:warmup --env=prod --no-debug

php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

chown -R www-data:www-data var

exec "$@"
