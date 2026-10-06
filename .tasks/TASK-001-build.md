# TASK-001 — Fiabiliser le script de build complet

**Type** : fix
**Statut** : ✅ fait le 2026-10-06
**Session** : non disponible (Codex)

Conserver la simplification locale de `build.sh` et corriger ses changements de comportement.
Carte cadrée après coup, à la demande de correction puis de commit.

## Livré

- `build.sh` : commandes contrôlées, chemin des ressources corrigé, copie facultative
  des fichiers de déploiement avec arrêt sur erreur, sources conservées, aucun `chown`.
- Documentation du fonctionnement et des prérequis dans `deployment/README.md`.
- Tests isolés du build avec commandes simulées et système de fichiers temporaire.
- Restauration de `site-sync.sh` et des quatre bibliothèques JavaScript supprimées :
  ces différences locales ne sont pas retenues.

## Vérification

**Tests** : `python3 nuxt-modern-website/deployment/tests/test_build.py` — 4 tests OK.
Absence de fichiers facultatifs, copie sans déplacement, échec de génération et échec
 de copie ; les deux échecs conservent le site précédent.
`sh -n build.sh`, `bash -n site-sync.sh` et `git diff --check` : réussite.

## Définition de terminé

- [x] Refactorisation conservée et changements involontaires corrigés.
- [x] Tests comportementaux exécutés.
- [x] Documentation à jour.

## Reste à faire (hors périmètre)

Le build Nuxt réel et le déploiement serveur n’ont pas été exécutés.
