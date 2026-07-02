.PHONY: up down logs shell migrate seed reset

# Lance l'environnement de test (build + démarrage)
up:
	docker compose up -d --build

# Arrête l'environnement
down:
	docker compose down

# Affiche les logs en direct
logs:
	docker compose logs -f app

# Accès au terminal dans le conteneur
shell:
	docker compose exec app bash

# Migrations uniquement
migrate:
	docker compose exec app php artisan migrate --force

# Migrations + données de démo
seed:
	docker compose exec app php artisan migrate --seed --force

# Repart de zéro (supprime le volume MySQL)
reset:
	docker compose down -v
	docker compose up -d --build
