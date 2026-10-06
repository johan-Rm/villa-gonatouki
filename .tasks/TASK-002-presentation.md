# TASK-002 — Préparer le dépôt pour une lecture publique

**Type** : chore
**Statut** : ✅ fait le 2026-10-06
**Session** : non disponible (Codex)

Demande : rendre le dépôt présentable à des recruteurs et publier une seule branche.
Réécriture de l’historique autorisée explicitement après découverte de secrets et
sauvegardes dans les anciens commits. Carte rédigée après le cadrage conversationnel.

## Livré

- README racine : contexte, architecture, parcours de lecture et limites réelles.
- Guides backend, frontend et installation corrigés (Nuxt 2, tests disponibles).
- Exemples d’environnement sans identifiants de production.
- Secrets Google/FCM et accès de synchronisation externalisés.
- Sauvegardes, `.env`, fichiers système et paramètres d’éditeur retirés du suivi.
- CI légère et tests de publication/synchronisation ajoutés.
- Historique antérieur sauvegardé localement dans un bundle ignoré par Git.

## Vérification

**Tests** : 4 tests de build + 4 tests de dépôt/synchronisation : OK.
Compose : `docker compose --env-file .env.example config --quiet` réussit.
Syntaxe Bash, sh et PHP des fichiers modifiés : OK ; `git diff --check` : OK.
Les tests de publication recherchent les formats de clés connus, sans prétendre
constituer un audit exhaustif de sécurité.

## Décisions de cadrage

Conserver les dossiers et chemins applicatifs. Publier une seule branche `master`
avec un nouvel historique de présentation ; sauvegarder l’ancien historique localement.
La permission de réécrire a été donnée avant toute modification de l’historique distant.

## Définition de terminé

- [x] Présentation fondée sur le code et les versions déclarées.
- [x] Fichiers privés identifiés exclus de la publication.
- [x] Contrôles ciblés et documentation présents.

## Reste à faire (hors périmètre)

Renouveler les identifiants précédemment publiés. Les copies et caches externes de
l’ancien historique peuvent subsister. Le démarrage applicatif complet et la
modernisation des dépendances ne sont pas validés ici.
