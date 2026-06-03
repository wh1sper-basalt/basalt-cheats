.PHONY: up down lint import-sql

up:
	docker compose up -d --build

down:
	docker compose down

lint:
	@php -v >/dev/null
	@for f in $$(find ./site -name '*.php' -not -path './site/vendor/*'); do php -l $$f || exit 1; done

import-sql:
	mysql -h 127.0.0.1 -P 3308 -u root -pbasalt_root < site/database/FULL.sql
