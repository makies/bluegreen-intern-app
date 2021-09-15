.PHONY: init
init: src/.env src/vendor up key-generate migrate seed

.PHONY: up
up:
	./vendor/bin/sail up -d

.PHONY: down
down:
	./vendor/bin/sail down

#.PHONY: build
#build:
#	docker-compose build --no-cache

.PHONY: re
re:
	make down && make up

.PHONY: ps
ps:
	docker-compose ps

.PHONY: bash
bash:
	./vendor/bin/sail bash

src/vendor:
	./vendor/bin/sail php composer install --no-progress --no-suggest

src/.env:
	@cp .env.example .env


.PHONY: migrate
migrate: src/.env src/vendor
	./vendor/bin/sail php artisan migrate

.PHONY: seed
seed: src/.env src/vendor
	./vendor/bin/sail php artisan db:seed

.PHONY: tinker
tinker: src/.env src/vendor
	./vendor/bin/sail php artisan tinker

.PHONY: test
test: src/.env src/vendor
	./vendor/bin/sail php artisan test

.PHONY: coverage
coverage: src/.env src/vendor
	#./vendor/bin/sail php XDEBUG_MODE=coverage
	./vendor/bin/sail php -d memory_limit=-1 ./vendor/bin/phpunit --testdox --coverage-html storage/report/ ./tests

.PHONY: cs
cs: src/.env src/vendor
	./vendor/bin/sail php ./vendor/bin/phpcs -s --colors ./app

.PHONY: cbf
cbf: src/.env src/vendor
	./vendor/bin/sail php ./vendor/bin/phpcbf -s --colors ./app

.PHONY: stan
stan: src/.env src/vendor
	./vendor/bin/sail php -d memory_limit=-1 ./vendor/bin/phpstan analyse

#.PHONY: ide-helper
#ide-helper: src/.env src/vendor
#	docker-compose run --rm app php artisan ide-helper:generate
#	docker-compose run --rm app php artisan ide-helper:meta
#	docker-compose run --rm app php artisan ide-helper:models --nowrite
#	#docker-compose run --rm app php artisan lighthouse:ide-helper
