# Backend principal — Digital Management System

Application Symfony 4.2 / PHP 7.4 servant l’administration et l’API du site Villa
Gonatouki. Doctrine représente notamment les hébergements, chambres, activités,
contenus, médias et éléments de facturation.

- `src/Entity/` : modèle métier.
- `src/Controller/` : contrôleurs et endpoints.
- `src/Command/` : exports JSON, génération de routes Nuxt, traductions et médias.
- `src/Service/Export/` : export des contenus consommés par le frontend.
- `templates/` : interfaces Twig.

L’environnement local est décrit dans [SETUP.md](../SETUP.md). Les données, clés JWT
et secrets doivent être préparés localement ; `.env.example` sert de point de départ.
