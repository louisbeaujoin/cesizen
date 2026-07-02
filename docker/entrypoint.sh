#!/bin/bash
set -e

# Génère une clé applicative si elle n'est pas fournie en variable d'environnement
[ -z "$APP_KEY" ] && php artisan key:generate --force

# Attend que MySQL soit prêt, puis exécute les migrations
until php artisan migrate --force 2>&1; do
    echo "Base de données non disponible, nouvelle tentative dans 3s..."
    sleep 3
done

# Insère les données de démo si demandé (DB_SEED=true)
if [ "${DB_SEED:-false}" = "true" ]; then
    php artisan db:seed --force
fi

# Démarre Apache en avant-plan (nécessaire pour Docker)
exec apache2-foreground
