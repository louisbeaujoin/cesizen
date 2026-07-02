# Fiche Q&R — Questions probables du jury

---

## DÉPLOIEMENT

**Q : Pourquoi Docker et pas WAMP pour l'environnement de test ?**
> Docker garantit que l'environnement est identique pour tout le monde et reproductible en une commande. Avec WAMP, "ça marche chez moi" mais pas forcément chez quelqu'un d'autre. Docker élimine ce risque.

**Q : Pourquoi GitHub Actions et pas Jenkins ou GitLab CI ?**
> GitHub Actions est intégré directement dans GitHub, donc zéro configuration externe. Comme j'utilise déjà GitHub pour le code et les tickets, tout est au même endroit. C'est simple à montrer et à expliquer.

**Q : C'est quoi l'intérêt du Makefile ?**
> C'est un raccourci pour éviter de taper des commandes Docker longues. `make up` remplace `docker compose up -d --build`. Ça rend le projet accessible à quelqu'un qui ne connaît pas Docker.

**Q : Comment tu déploies en production réellement ?**
> En production, ce serait un VPS Linux avec Nginx + PHP-FPM. On clonerait le dépôt, on configurerait le .env avec les vraies valeurs, on lancerait `php artisan migrate --force` et `php artisan config:cache`. Le SSL serait géré par Let's Encrypt avec certbot. Dans ce projet, la production est décrite théoriquement car l'environnement réellement configuré est l'environnement de test Docker.

**Q : Qu'est-ce que le Semantic Versioning ?**
> C'est une convention de numérotation des versions : MAJEUR.MINEUR.PATCH. 1.0.0 = première version stable. 1.1.0 = nouvelle fonctionnalité. 1.0.1 = correctif de bug. Ça permet à tout le monde de comprendre l'ampleur d'un changement rien qu'en regardant le numéro.

---

## MAINTENANCE

**Q : Pourquoi GitHub Issues plutôt que Jira ou Trello ?**
> Parce que ça centralise tout dans le même outil : code, CI/CD, tickets. Jira nécessite un abonnement et une configuration séparée. Le sujet mentionne que c'est "un plus apprécié" d'utiliser un seul outil pour tout.

**Q : Comment tu gères les SLA en pratique ?**
> Quand un ticket arrive, je le qualifie en criticité dans l'heure. Pour un bloquant critique, je commence la correction immédiatement, avec un délai de 3h ouvrées. Pour un mineur, je le regroupe avec d'autres mineurs dans un lot hebdomadaire pour optimiser le temps de développement.

**Q : C'est quoi Dependabot ?**
> C'est un outil GitHub qui surveille automatiquement les dépendances du projet (Composer pour PHP, npm pour JavaScript). Quand une mise à jour est disponible, il ouvre automatiquement une Pull Request. Les tests CI s'exécutent dessus, et je la merge si tout est vert.

---

## SÉCURITÉ

**Q : Qu'est-ce que l'OWASP Top 10 ?**
> C'est une liste des 10 vulnérabilités les plus courantes dans les applications web, publiée par l'OWASP (Open Web Application Security Project). C'est la référence mondiale pour l'analyse de sécurité. J'ai vérifié mon code sur chacun de ces 10 points.

**Q : Comment Laravel protège contre l'injection SQL ?**
> Laravel utilise Eloquent, qui génère des requêtes préparées avec paramétrage. Concrètement, les valeurs utilisateur sont séparées du code SQL — le moteur de base de données ne peut pas confondre une valeur avec une instruction SQL.

**Q : C'est quoi le rate limiting et pourquoi c'est important ?**
> Le rate limiting limite le nombre de requêtes qu'une même IP peut envoyer en un temps donné. Sur le formulaire de connexion, j'ai mis 5 tentatives par minute. Sans ça, un attaquant peut tester des milliers de mots de passe automatiquement (brute force). Avec ça, il lui faudrait des années.

**Q : Pourquoi les en-têtes HTTP de sécurité ?**
> X-Frame-Options empêche quelqu'un d'intégrer CESIZen dans une iframe sur un site malveillant pour tromper les utilisateurs (clickjacking). X-Content-Type-Options empêche le navigateur de deviner le type d'un fichier, ce qui peut éviter des exécutions de code non voulues.

**Q : C'est quoi le RGPD et qu'est-ce que tu as mis en place ?**
> Le RGPD est le règlement européen sur la protection des données. Pour CESIZen, j'ai appliqué trois principes : minimisation (je collecte seulement nom + email + historique de sessions, rien d'autre), durées de conservation définies (2 ans pour les sessions), et droits des utilisateurs accessibles (modification et suppression du profil). La CNIL doit être notifiée dans les 72h en cas de fuite de données.

**Q : Et si quelqu'un pirate l'application, qu'est-ce qui se passe ?**
> Il y a un plan de crise en 7 étapes : détection, qualification de la gravité, notification au Ministère (DSI + DPO) dans les 2h, mise hors ligne si nécessaire, déclaration CNIL obligatoire sous 72h, communication aux utilisateurs concernés, puis rapport post-incident avec plan de remédiation.

---

## GÉNÉRAL

**Q : Qu'est-ce que tu ferais différemment si c'était un vrai projet ?**
> Je mettrais en place le déploiement automatique en production (CD) via GitHub Actions avec une approbation manuelle avant le déploiement prod. J'ajouterais aussi un outil de monitoring (type Sentry pour les erreurs, Uptime Robot pour la disponibilité) et l'export des données RGPD pour le droit à la portabilité.

**Q : Quel est le budget pour ton architecture ?**
> L'environnement de développement est gratuit (WAMP local). L'environnement de test Docker est gratuit. En production, un VPS basique OVHcloud coûte environ 5-10€/mois, plus le nom de domaine (~10€/an). GitHub et GitHub Actions sont gratuits pour les dépôts publics. Le tout reste très largement dans l'enveloppe de 75 000€.
