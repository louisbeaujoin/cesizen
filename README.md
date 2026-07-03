# CESIZen

Plateforme web de bien-être mental développée pour un Ministère fictif dans le cadre d'un projet de formation CDA CESI.

## Présentation

CESIZen propose des exercices de respiration guidés et des pages d'information sur la santé mentale, accessibles sans inscription. Les utilisateurs connectés disposent d'un espace personnel pour suivre leur historique de sessions. Un back-office permet aux administrateurs de gérer les contenus et les utilisateurs.

## Stack technique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| Framework | Laravel | 13 |
| Langage | PHP | 8.3 |
| CSS / Build | Tailwind CSS + Vite | 4 / 7 |
| Base de données | MySQL | 8.0 |
| Tests | Pest | 4.x |
| Conteneur | Docker + Apache | PHP 8.3 |
| CI/CD | GitHub Actions | — |

## Démarrage rapide — Environnement DEV (WAMP)

```bash
git clone https://github.com/louisbeaujoin/cesizen.git
cd cesizen

composer install
npm install

cp .env.example .env
php artisan key:generate

# Configurer DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env
php artisan migrate --seed

composer dev   # Lance PHP + Vite en parallèle → localhost:8000
```

## Environnement TEST — Docker Compose

Prérequis : Docker Desktop (≥ 4 Go RAM alloués, ≥ 3 Go disque disponible).

```bash
make up      # Build de l'image + démarrage app (port 8080) + db (MySQL 8)
make seed    # Migrations + données de démo

# Accès : http://localhost:8080
# Admin : admin@cesizen.fr / password
# User  : user@cesizen.fr  / password

make down    # Arrêt propre
make reset   # Supprime les données et repart de zéro
```

| Commande | Action |
|----------|--------|
| `make up` | Build + démarrage |
| `make seed` | Migrations + seeders |
| `make logs` | Logs en temps réel |
| `make shell` | Terminal dans le conteneur |
| `make down` | Arrêt |
| `make reset` | Remise à zéro complète |

## Tests automatisés

```bash
composer test
# → 47 tests Pest, SQLite en mémoire, ~2 secondes
```

Le pipeline CI GitHub Actions exécute ces tests à chaque push sur `main` ou `develop`. Le build Docker ne démarre que si les 47 tests passent.

## Architecture des branches

```
main        ← code stable, livrable en production (merge via PR uniquement)
develop     ← intégration continue des développements
feature/*   ← fonctionnalité isolée, créée depuis develop
```

Conventions de commit : [Conventional Commits](https://www.conventionalcommits.org/)
Versioning : [Semantic Versioning](https://semver.org/) — version actuelle `v1.0.0`

## Sécurité

- Protection brute force : `throttle:5,1` sur les routes de connexion
- En-têtes HTTP de sécurité : `SecurityHeadersMiddleware` (X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy)
- Protection XSS : cast `(int)` sur les paramètres injectés en JavaScript + Blade `{{ }}` natif
- Protection CSRF : token `@csrf` sur tous les formulaires
- Mots de passe : bcrypt 12 rounds via cast `'hashed'` sur le modèle User
- Secrets : `.env` exclu de git, `APP_DEBUG=false` dans `.env.example`

Pour signaler une vulnérabilité : ouvrir une issue confidentielle sur GitHub.

## Documentation

- [Dossier technique Bloc 3](document/CESIZen_Dossier_Bloc3.pdf) — plan de déploiement, maintenance et sécurisation
- [Présentation soutenance](document/CESIZen_Presentation_Bloc3.pdf) — 15 slides
- [Script de démo](document/script_demo.md)
- [Suivi du projet](https://github.com/louisbeaujoin/cesizen/projects) — board GitHub Projects

## Licence

Projet de formation — usage éducatif uniquement.
