up: docker-up
down: docker-down
restart: down up
init: docker-down-clear docker-pull docker-build up init-db
test: run-tests
unit-tests: run-tests-unit
init-db: dropdb createdb migrate

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build

docker-pull:
	docker-compose pull

migrate:
	docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate --env=test --no-interaction

dropdb:
	docker-compose run --rm php-fpm php bin/console doctrine:database:drop --force --env=test --no-interaction

createdb:
	docker-compose run --rm php-fpm php bin/console doctrine:database:create --env=test --no-interaction

run-tests:
	docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load --env=test --no-interaction
	docker-compose run --rm php-fpm php bin/phpunit

run-tests-unit:
	docker-compose run --rm php-fpm php bin/phpunit --testsuite=unit