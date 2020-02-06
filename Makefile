up: docker-up
down: docker-down
test: dropdb createdb migrate run-tests

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

migrate:
	docker-compose run php-fpm php bin/console doctrine:migrations:migrate --env=test --no-interaction

dropdb:
	docker-compose run php-fpm php bin/console doctrine:database:drop --force --env=test --no-interaction

createdb:
	docker-compose run php-fpm php bin/console doctrine:database:create --env=test --no-interaction

run-tests:
	docker-compose run php-fpm php bin/phpunit