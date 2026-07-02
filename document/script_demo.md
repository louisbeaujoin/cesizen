# Script de démo — Soutenance Bloc 3 CESIZen

## Durée totale : ~6 minutes (dans les 20 min de soutenance)

---

## PARTIE A — Démo versioning (3 min)

### Étape 1 — Montrer l'historique Git sur GitHub (30 sec)
1. Ouvrir **github.com/louisbeaujoin/cesizen**
2. Cliquer sur **"5 commits"** (ou le nombre affiché)
3. Montrer les commits : initial → Docker → templates → sécurité
4. **Dire :** "Chaque commit correspond à une étape précise du projet, avec un message conventionnel qui explique ce qui a été fait et pourquoi."

### Étape 2 — Montrer le pipeline CI en action (1 min 30)
1. Cliquer sur l'onglet **Actions**
2. Montrer le dernier workflow exécuté
3. Cliquer dessus pour voir le détail des deux jobs
4. Montrer **Job 1 "Tests Pest"** → les 44 tests passent
5. Montrer **Job 2 "Build Docker"** → image construite
6. **Dire :** "À chaque push sur GitHub, les tests s'exécutent automatiquement. Si un test échoue, l'image Docker n'est pas construite. C'est le filet de sécurité qui empêche du code cassé d'atteindre l'environnement de test."

### Étape 3 — Simuler un push en live (1 min)
*(Optionnel si le temps le permet — préparer en avance)*
1. Dans VS Code, modifier une ligne mineure (ex : commentaire dans un contrôleur)
2. Dans le terminal :
```bash
git add app/Http/Controllers/HomeController.php
git commit -m "docs: demo pipeline CI en soutenance"
git push origin develop
```
3. Basculer sur GitHub → onglet Actions → montrer le workflow qui démarre
4. **Dire :** "Le pipeline se déclenche en quelques secondes après le push."

---

## PARTIE B — Démo ticketing (3 min)

### Étape 1 — Montrer le board GitHub Projects (1 min)
1. Aller sur **github.com/louisbeaujoin/cesizen** → onglet **Projects**
2. Ouvrir **"CESIZen — Suivi du projet"**
3. Montrer les 4 colonnes et les tickets positionnés
4. **Dire :** "Le board reflète l'état réel du projet. On voit immédiatement ce qui est bloquant, ce qui est en cours, ce qui attend une validation."

### Étape 2 — Ouvrir un ticket (1 min)
1. Cliquer sur le ticket **#13** (bug bloquant critique — erreur 500 connexion)
2. Montrer le formulaire rempli : description, étapes de reproduction, criticité cochée
3. Montrer les labels : `bug` + `bloquant-critique`
4. **Dire :** "Quand le Ministère signale un incident, il utilise le template pré-rempli. La criticité est sélectionnée, ce qui déclenche le SLA correspondant : prise en compte sous 1h, correction sous 3h."

### Étape 3 — Traiter un ticket (1 min)
1. Montrer le ticket **#16** (maintenance — mise à jour Laravel) en colonne "Terminé"
2. **Dire :** "Ce ticket a été généré automatiquement par Dependabot. J'ai vérifié le CHANGELOG, lancé les tests, et mergé. C'est le processus de veille technologique automatisé."
3. Montrer l'onglet **Issues** → filtrer par label `bloquant-critique`
4. **Dire :** "En soutenance, si le jury veut créer un nouveau ticket, je peux le faire en live en moins de 30 secondes."

---

## Points de transition à mémoriser

- Après la démo versioning : *"Ce pipeline CI/CD garantit que seul du code testé peut être déployé."*
- Après la démo ticketing : *"L'avantage d'utiliser GitHub pour tout — code + CI + tickets — c'est la traçabilité : chaque ticket peut être lié directement au commit qui le corrige."*
- Si une question sur Docker : *"L'environnement Docker est lancé avec `make up`. Je peux le démarrer en live si vous souhaitez le voir tourner."*
