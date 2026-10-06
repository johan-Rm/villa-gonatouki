# Build complet

`build.sh` s’exécute sur le serveur dans `/var/www/villa-gonatouki/nuxt-modern-website`.
Il génère le site dans `dist_tmp`, puis remplace `dist` après une génération réussie.
Les fichiers facultatifs `deployment/.htaccess` et `deployment/robots.txt` sont copiés
s’ils existent ; une erreur de copie arrête le build avant le remplacement de `dist`.
Les sources de ces fichiers sont conservées pour les builds suivants.

Le script vide le cache des routes et conserve les permissions historiques (`777`)
sur le cache Node, `dist` et `/var/www/resources/villa-gonatouki`.
Il ne change pas leur propriétaire. Ces chemins doivent exister et être accessibles.

Vérification isolée, sans génération Nuxt ni déploiement :

```sh
python3 nuxt-modern-website/deployment/tests/test_build.py
```
