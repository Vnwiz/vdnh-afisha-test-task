#Makefile для удобства поднятия сервисов локально. Пример использования: make up
up:
	docker compose -f docker-compose.local.yml up -d

down:
	docker compose -f docker-compose.local.yml down

build:
	docker compose -f docker-compose.local.yml build

docs:
	make clear
	docker compose -f docker-compose.local.yml exec php php artisan scribe:generate

clear:
	docker compose -f docker-compose.local.yml exec php php artisan optimize:clear

migrate:
	docker compose -f docker-compose.local.yml exec php php artisan migrate --force

seed:
	docker compose -f docker-compose.local.yml exec php php artisan db:seed --force

migrate-fresh:
	docker compose -f docker-compose.local.yml exec php php artisan migrate:fresh --seed --force

exec:
	docker exec -it vdnh-afisha-laravel bash

rebuild:
	make down
	make build
	make up
	make migrate-fresh
	make docs
