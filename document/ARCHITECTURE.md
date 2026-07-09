# Architecture du projet CesiZen

CesiZen est une application web Laravel dédiée au bien-être mental. Elle propose des exercices de respiration guidés et des pages d'information sur la santé mentale.

---

## Structure générale (MVC)

Laravel suit le patron MVC — **Modèle / Vue / Contrôleur** — auquel s'ajoutent des couches propres à Laravel : middleware, seeders, migrations et providers.

```
cesizen/
├── app/
│   ├── Http/
│   │   ├── Controllers/        ← Contrôleurs
│   │   └── Middleware/         ← Middleware
│   ├── Models/                 ← Modèles
│   └── Providers/              ← Providers
├── resources/
│   ├── views/                  ← Vues (Blade)
│   ├── css/                    ← CSS source
│   └── js/                     ← JS source
├── routes/
│   └── web.php                 ← Définition des routes
├── database/
│   ├── migrations/             ← Structure de la base de données
│   ├── seeders/                ← Données initiales
│   └── factories/              ← Données de test
├── public/
│   └── css/                    ← CSS compilés
└── tests/
    ├── Feature/                ← Tests fonctionnels
    └── Unit/                   ← Tests unitaires
```

---

## Modèles (`app/Models/`)

Les modèles représentent les tables de la base de données et les relations entre elles.

| Fichier                  | Table                   | Description |
|--------------------------|-------------------------|-------------|
| `User.php`               | `users`                 | Utilisateur (rôle : `user` ou `admin`, statut actif/inactif) |
| `BreathingExercise.php`  | `breathing_exercises`   | Exercice de respiration prédéfini (durées inspiration/apnée/expiration) |
| `BreathingSession.php`   | `breathing_sessions`    | Session de respiration effectuée par un utilisateur |
| `InformationPage.php`    | `information_pages`     | Page d'information sur la santé mentale (avec slug URL) |

### Relations entre modèles

```
User ──────────────────────────── BreathingSession (hasMany)
                                         │
BreathingExercise ─────────────── BreathingSession (hasMany)
```

- Un `User` peut avoir plusieurs `BreathingSession`
- Un `BreathingExercise` peut avoir plusieurs `BreathingSession`
- Une `BreathingSession` appartient à un `User` (nullable) et à un `BreathingExercise` (nullable)

### Scopes utiles

| Modèle                 | Scope             | Effet |
|------------------------|-------------------|-------|
| `BreathingExercise`    | `active()`        | Filtre les exercices avec `is_active = true` |
| `InformationPage`      | `published()`     | Filtre les pages avec `is_published = true` |
| `InformationPage`      | `ordered()`       | Trie par `sort_order` croissant |

---

## Contrôleurs (`app/Http/Controllers/`)

Les contrôleurs reçoivent les requêtes HTTP, interrogent les modèles et retournent des vues ou des réponses JSON.

### Contrôleurs publics

| Fichier                     | Rôle |
|-----------------------------|------|
| `HomeController.php`        | Affiche la page d'accueil avec les pages publiées |
| `AuthController.php`        | Inscription, connexion, déconnexion, profil utilisateur |
| `InformationController.php` | Affiche la liste et le détail des pages d'information |
| `BreathingController.php`   | Affiche les exercices, lance un exercice, enregistre une session |

### Contrôleurs admin (`Admin/`)

Accessibles uniquement aux utilisateurs avec le rôle `admin`.

| Fichier                             | Rôle |
|-------------------------------------|------|
| `DashboardController.php`           | Tableau de bord avec statistiques globales |
| `UserController.php`                | CRUD utilisateurs + activation/désactivation |
| `InformationPageController.php`     | CRUD pages d'information + génération du slug |
| `BreathingExerciseController.php`   | CRUD exercices de respiration |

---

## Vues (`resources/views/`)

Les vues sont des templates Blade (`.blade.php`). Elles héritent d'un layout parent via `@extends`.

### Layouts

| Fichier                        | Utilisé par |
|--------------------------------|-------------|
| `layouts/app.blade.php`        | Toutes les pages publiques |
| `layouts/admin.blade.php`      | Toutes les pages admin |

### Pages publiques

```
views/
├── home.blade.php                      ← Page d'accueil
├── auth/
│   ├── login.blade.php                 ← Formulaire de connexion
│   ├── register.blade.php              ← Formulaire d'inscription
│   └── profile.blade.php              ← Profil utilisateur
├── information/
│   ├── index.blade.php                 ← Liste des pages d'information
│   └── show.blade.php                  ← Détail d'une page
└── breathing/
    ├── index.blade.php                 ← Liste des exercices
    └── exercise.blade.php              ← Widget d'exercice animé
```

### Pages admin

```
views/admin/
├── dashboard.blade.php                 ← Statistiques
├── users/
│   ├── index.blade.php                 ← Liste des utilisateurs
│   ├── create.blade.php                ← Créer un utilisateur
│   └── edit.blade.php                  ← Modifier un utilisateur
├── information/
│   ├── index.blade.php                 ← Liste des pages
│   ├── create.blade.php                ← Créer une page
│   └── edit.blade.php                  ← Modifier une page
└── breathing/
    ├── index.blade.php                 ← Liste des exercices
    ├── create.blade.php                ← Créer un exercice
    └── edit.blade.php                  ← Modifier un exercice
```

---

## Routes (`routes/web.php`)

Les routes définissent les URLs de l'application et les associent aux contrôleurs.

### Routes publiques

| Méthode | URL                              | Action |
|---------|----------------------------------|--------|
| GET     | `/`                              | Page d'accueil |
| GET     | `/informations`                  | Liste des pages d'information |
| GET     | `/informations/{slug}`           | Détail d'une page |
| GET     | `/respiration`                   | Liste des exercices |
| GET     | `/respiration/exercice/{id?}`    | Lancer un exercice |

### Routes authentifiées (`middleware: auth`)

| Méthode | URL                        | Action |
|---------|----------------------------|--------|
| GET     | `/connexion`               | Afficher le formulaire de connexion |
| POST    | `/connexion`               | Traiter la connexion |
| GET     | `/inscription`             | Afficher le formulaire d'inscription |
| POST    | `/inscription`             | Créer un compte |
| POST    | `/deconnexion`             | Se déconnecter |
| GET     | `/profil`                  | Voir son profil |
| PUT     | `/profil`                  | Modifier son profil |
| PUT     | `/profil/mot-de-passe`     | Changer son mot de passe |
| POST    | `/respiration/session`     | Sauvegarder une session |

### Routes admin (`middleware: auth + admin`, préfixe `/admin`)

| Méthode | URL                                          | Action |
|---------|----------------------------------------------|--------|
| GET     | `/admin`                                     | Tableau de bord |
| GET/POST/PUT/DELETE | `/admin/utilisateurs/...`       | CRUD utilisateurs |
| GET/POST/PUT/DELETE | `/admin/informations/...`       | CRUD pages d'information |
| GET/POST/PUT/DELETE | `/admin/respiration/...`        | CRUD exercices |

---

## Middleware (`app/Http/Middleware/`)

| Fichier                  | Alias   | Rôle |
|--------------------------|---------|------|
| `AdminMiddleware.php`    | `admin` | Vérifie que l'utilisateur connecté est admin, sinon retourne une erreur 403 |

Le middleware Laravel `auth` (intégré) vérifie que l'utilisateur est connecté.  
Le middleware `guest` (intégré) vérifie que l'utilisateur n'est **pas** connecté.

---

## Base de données (`database/`)

### Migrations (dans l'ordre d'exécution)

| Fichier                                         | Table(s) créée(s) |
|-------------------------------------------------|-------------------|
| `0001_01_01_000000_create_users_table`          | `users`, `password_reset_tokens`, `sessions` |
| `0001_01_01_000002_create_jobs_table`           | `jobs`, `job_batches`, `failed_jobs` |
| `2024_01_01_000010_add_role_to_users_table`     | Ajoute `role` et `is_active` à `users` |
| `2024_01_01_000020_create_information_pages_table` | `information_pages` |
| `2024_01_01_000030_create_breathing_exercises_table` | `breathing_exercises` |
| `2024_01_01_000040_create_breathing_sessions_table` | `breathing_sessions` |

### Seeders

| Fichier                        | Données insérées |
|--------------------------------|------------------|
| `UserSeeder.php`               | 1 admin (`admin@cesizen.fr`) + 1 utilisateur test |
| `InformationPageSeeder.php`    | 4 pages : santé mentale, stress, cohérence cardiaque, quand consulter |
| `BreathingExerciseSeeder.php`  | 3 exercices : 7-4-8, 5-5, 4-6 |
| `DatabaseSeeder.php`           | Appelle les 3 seeders ci-dessus dans l'ordre |

---

## Assets frontend (`public/css/`, `resources/js/`)

| Fichier                    | Rôle |
|----------------------------|------|
| `public/css/app.css`       | Styles globaux : layout, navigation, formulaires, boutons, tableaux |
| `public/css/breathing.css` | Styles du widget de respiration animé (cercle, phases) |
| `public/css/admin.css`     | Surcharge mineure pour l'en-tête admin |
| `resources/js/app.js`      | Point d'entrée JS (importe bootstrap.js) |
| `resources/js/bootstrap.js`| Configure Axios pour les requêtes AJAX |

---

## Tests (`tests/`)

Les tests utilisent **Pest PHP** (syntaxe fonctionnelle).

### Tests fonctionnels (`tests/Feature/`)

| Fichier                  | Ce qui est testé |
|--------------------------|------------------|
| `AuthTest.php`           | Inscription, connexion, déconnexion, profil, mot de passe, sécurité admin |
| `BreathingTest.php`      | Affichage des exercices, lancement, sauvegarde de session, droits admin |
| `InformationTest.php`    | Affichage des pages, accès par slug, CRUD admin |

### Tests unitaires (`tests/Unit/`)

| Fichier                         | Ce qui est testé |
|---------------------------------|------------------|
| `UserModelTest.php`             | `isAdmin()`, hashage du mot de passe, `$fillable` |
| `BreathingExerciseModelTest.php`| `getCycleDuration()`, scope `active()`, relations |
| `InformationPageModelTest.php`  | Scope `published()`, scope `ordered()` |

---

## Flux d'une requête typique

Voici comment se déroule une requête HTTP dans l'application :

```
Navigateur
    │
    ▼
routes/web.php          ← Détermine quel contrôleur appeler
    │
    ▼
Middleware              ← Vérifie auth / admin si nécessaire
    │
    ▼
Contrôleur              ← Reçoit la requête, appelle les modèles
    │
    ▼
Modèle (Eloquent)       ← Interroge la base de données
    │
    ▼
Vue (Blade)             ← Génère le HTML final
    │
    ▼
Navigateur              ← Reçoit la réponse HTTP
```

---

## Sécurité

| Mécanisme | Implémentation |
|-----------|----------------|
| Authentification | Middleware `auth` de Laravel (sessions) |
| Autorisation admin | `AdminMiddleware` + méthode `isAdmin()` sur le modèle `User` |
| Protection CSRF | Token `@csrf` dans tous les formulaires (automatique Laravel) |
| Hashage des mots de passe | `Hash::make()` à l'écriture, cast `hashed` sur le modèle |
| Compte désactivé | Vérification de `is_active` à la connexion dans `AuthController` |
| Protection auto-suppression | L'admin ne peut pas supprimer ni désactiver son propre compte |
