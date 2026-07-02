# CESIZen Respiration — Android

Application Android Studio reproduisant l'exercice de respiration du projet CESIZen.

## Fonctionnalités

- 3 méthodes prédéfinies : 7-4-8, 5-5 (cohérence cardiaque), 4-6
- Mode personnalisé : inspiration / apnée / expiration / nombre de cycles configurables
- Cercle animé qui s'agrandit pendant l'inspiration et se rétracte pendant l'expiration
- Compteur de cycles, décompte par phase, durée totale en fin d'exercice

## Ouvrir le projet

1. Ouvrir Android Studio (Hedgehog ou plus récent recommandé)
2. **File > Open** → sélectionner le dossier `android/`
3. Attendre la synchronisation Gradle (le wrapper télécharge automatiquement Gradle 8.7)
4. Lancer sur un émulateur ou un appareil (Android 8.0+ / API 26+)

## Stack

- Kotlin
- Material Components 3 (Theme.Material3.DayNight)
- ViewBinding
- AGP 8.5.2 / Gradle 8.7 / Kotlin 1.9.24

## Structure

- `MainActivity` : liste des exercices + formulaire personnalisé
- `ExerciseActivity` : exécution de l'exercice avec animation du cercle
- `BreathingExercise` : modèle de données + presets
