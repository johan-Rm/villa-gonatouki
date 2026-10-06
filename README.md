# Villa Gonatouki

Site multilingue et outils de gestion pour une maison d’hôtes à Essaouira, au Maroc.
Le projet associe un site public Nuxt à un backend Symfony : gestion du contenu,
hébergements, activités, galeries, traductions et génération de pages statiques.

Ce dépôt présente un projet existant et ses choix techniques. Les dépendances sont
celles d’une stack historique ; la remise en route complète et leur modernisation
restent à valider. Les données de production et les identifiants ne sont pas fournis.

## Architecture

| Dossier | Rôle | Technologies |
| --- | --- | --- |
| [nuxt-modern-website](nuxt-modern-website/) | Site public, pages et génération statique | Nuxt 2, Vue 2, Vuetify, i18n |
| [digital-management-system](digital-management-system/) | Administration, API, données et exports vers le frontend | Symfony 4.2, PHP 7.4, Doctrine, API Platform, EasyAdmin |
| [digital-management-system-sylius](digital-management-system-sylius/) | Variante du backend fondée sur Sylius, conservée comme composant historique distinct | Sylius 1.10, Symfony, Twig |
| [docker-compose.yml](docker-compose.yml) et [nginx](nginx/) | Environnement de développement du backend principal et du frontend | Docker, MySQL 8, Nginx, MailHog |

Le backend principal partage avec Nuxt les routes générées, les traductions et les
médias. Nuxt consomme l’API et peut générer le site dans `dist_tmp`. La variante
Sylius n’est pas démarrée par le Compose racine.

## Parcours de lecture

Pour examiner le travail métier et les échanges entre applications :

- [Entités métier](digital-management-system/src/Entity/) : hébergements, chambres, activités, contenus et facturation.
- [Commandes Symfony](digital-management-system/src/Command/) : routes Nuxt, exports JSON, traductions et traitement des médias.
- [Export API vers JSON](digital-management-system/src/Service/Export/ApiToJson.php).
- [Pages du site public](nuxt-modern-website/pages/) et [configuration Nuxt](nuxt-modern-website/nuxt.config.js).
- [Scripts et tests de build](nuxt-modern-website/deployment/).

Les frameworks et bibliothèques fournissent une partie importante du socle ; les
manifestes et fichiers de verrouillage décrivent les dépendances utilisées.

## Installation et vérification

Suivre [SETUP.md](SETUP.md) pour configurer les fichiers locaux et les services.
Le dépôt contient des exemples de configuration, sans accès à la production.

Le test isolé du script de build ne nécessite ni Docker ni dépendances applicatives :

```sh
python3 nuxt-modern-website/deployment/tests/test_build.py
python3 tests/test_repository.py
```

La [CI](.github/workflows/checks.yml) vérifie ce test et la syntaxe des scripts
modifiés. Elle ne constitue pas une validation complète des applications.

## État du projet

- Le backend principal et le frontend disposent d’une configuration Docker locale.
- Les routes, traductions et médias nécessaires à un rendu complet dépendent du contenu métier.
- Les scripts de synchronisation et de déploiement sont des outils d’exploitation ; ils ne sont pas nécessaires à la lecture du code.
- La variante Sylius requiert une configuration spécifique, notamment `config/project.yaml`, qui n’est pas fournie.
- Les tests de build passent ; le lancement complet des applications n’a pas été vérifié lors de la préparation de ce dépôt.

Le dépôt conserve une seule branche, `master`. Les sauvegardes, configurations
locales et exports de bases sont exclus du suivi Git. Aucun droit supplémentaire
sur les contenus, visuels ou dépendances tiers n’est accordé par cette présentation.
