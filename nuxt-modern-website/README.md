# Site public — Nuxt 2

Frontend Vue 2 / Nuxt 2 de Villa Gonatouki, avec Vuetify et internationalisation.
Les pages présentent la villa, ses chambres, activités, services et événements.
Le frontend consomme le backend et des fichiers de contenu générés.

- `pages/` et `components/` : pages et composants Vue.
- `nuxt.config.js` : modules, API, langues et génération dans `dist_tmp`.
- `deployment/` : scripts serveur et tests isolés du build.

Configuration locale : [SETUP.md](../SETUP.md).

```sh
yarn install --frozen-lockfile
yarn dev
```

Les scripts `build`, `start` et `generate` sont également déclarés dans `package.json`.
Un build complet dépend de l’accès aux contenus et à l’API configurés dans `.env`.
La stack et les dépendances sont historiques ; la remise en route complète reste à valider.
