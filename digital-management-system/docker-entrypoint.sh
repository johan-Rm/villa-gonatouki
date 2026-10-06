#!/bin/bash
set -e

# 🛠 Crée les chemins nécessaires s'ils n'existent pas (uploads & cache LiipImagine)
mkdir -p /var/www/html/var
mkdir -p /var/www/html/public/uploads/media
mkdir -p /var/www/html/public/media/cache

# 🛠 Crée le dossier partagé avec le frontend si besoin (Nuxt routes)
mkdir -p /app/update 
mkdir -p /app/lang

# 🔐 Fixe les permissions pour Symfony + Nuxt routes partagées
chown -R www-data:www-data \
        /var/www/html/var \
        /var/www/html/translations \
        /var/www/html/public/uploads \
        /var/www/html/public/media \
        /app/update \
        /app/lang

chmod -R 775 /app/update
chmod -R 775 /app/lang
chmod -R 775 /var/www/html/translations

# 🚀 (optionnel) Warmup cache Symfony
# su www-data -s /bin/bash -c "php bin/console cache:warmup"

exec "$@"
