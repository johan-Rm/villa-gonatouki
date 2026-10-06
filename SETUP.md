# Environnement de développement

Le Compose racine démarre le backend Symfony principal, le frontend **Nuxt 2**,
MySQL 8, Nginx et MailHog. La variante Sylius est indépendante.
Les images utilisent PHP 7.4 et Node 16 ; ce guide décrit la configuration existante,
sans promettre une installation validée sur les environnements actuels.

## Configuration locale

Depuis la racine :

```sh
cp .env.example .env
cp digital-management-system/.env.example digital-management-system/.env
cp nuxt-modern-website/.env.example nuxt-modern-website/.env
```

Choisir ses mots de passe MySQL dans `.env`. Le Compose transmet la connexion au
backend ; conserver des valeurs compatibles dans son `.env` si on l’utilise hors
Compose. Remplacer `APP_SECRET` et `JWT_PASSPHRASE` par des valeurs locales.
Les exemples contiennent uniquement des valeurs de développement.

`GOOGLE_MAPS_API_KEY` est facultatif pour la configuration du frontend ; les fonctions
Google Maps et l’export d’avis nécessitent une clé valide et configurée séparément.
Le frontend appelle l’API exposée sur `http://localhost:8080/api`.

## Services et dépendances

```sh
docker compose config --quiet
docker compose up -d --build
docker compose exec backend composer install
docker compose exec backend yarn install --ignore-engines
docker compose exec backend yarn encore dev
```

L’image frontend installe les dépendances Yarn et lance `yarn dev`.
Le backend utilise des volumes locaux : les dépendances PHP doivent être installées
dans le volume par la commande ci-dessus.

## Données et clés JWT

Le dépôt ne contient pas de copie de la base de production. Préparer une base
locale et des données de démonstration adaptées au schéma avant de tester le site.
Vérifier les migrations disponibles avant de les appliquer :

```sh
docker compose exec backend php bin/console doctrine:migrations:status
```

L’authentification JWT attend les clés indiquées dans le `.env` du backend. Générer
une paire locale avec OpenSSL, en utilisant la même passphrase que `JWT_PASSPHRASE` :

```sh
mkdir -p digital-management-system/config/jwt
openssl genrsa -aes256 -out digital-management-system/config/jwt/private.pem 2048
openssl rsa -pubout -in digital-management-system/config/jwt/private.pem -out digital-management-system/config/jwt/public.pem
```

Les exports JSON, routes Nuxt, traductions et médias sont alimentés par le backend.
Un clone sans données ne fournit donc pas une démonstration complète du site.
Ne pas importer une base de production pour une démonstration publique.

## Adresses locales

| Service | Adresse |
| --- | --- |
| Site Nuxt | http://localhost:3000 |
| Backend / API | http://localhost:8080/api |
| MailHog | http://localhost:8025 |
| MySQL | localhost:3306 |

## Vérifications disponibles

```sh
python3 nuxt-modern-website/deployment/tests/test_build.py
sh -n nuxt-modern-website/deployment/build.sh
bash -n site-sync.sh
```

Ces contrôles valident le script de build en isolation. Aucun script `yarn test`
n’est déclaré dans le frontend. La CI ne lance pas les applications ni les tests
historiques Sylius.

## Outils d’exploitation

`site-sync.sh` synchronise les médias et peut remplacer le contenu de la base locale.
Il nécessite un accès autorisé au serveur distant, les variables `SYNC_*` et
`DB_PROD_*` de `.env`, ainsi que l’ancien exécutable `docker-compose`.
Consulter `bash site-sync.sh --help` avant toute utilisation ; il n’est pas nécessaire
pour installer ou consulter le dépôt.

Le [build complet](nuxt-modern-website/deployment/README.md) utilise des chemins
serveur fixes. Il doit être exécuté uniquement dans l’environnement prévu.

## Variante Sylius

`digital-management-system-sylius` est conservé séparément pour montrer cette
variante. Son `.env.example` décrit les variables attendues, mais son fichier
`config/project.yaml` et sa configuration d’exploitation ne sont pas fournis.
Sa procédure de lancement n’est pas validée ici.
