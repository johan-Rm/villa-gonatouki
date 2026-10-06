# Utilise PHP 7.4 avec FPM
FROM php:7.4-fpm

# Installe les dépendances système
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        curl \
        gnupg \
        webp \
    && docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql zip intl opcache mbstring xml gd

# Installe Node 16.2.0 + npm + Yarn 1.22.19
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@7.13.0 \
    && npm install -g yarn@1.22.19

# Installe Composer 2.6.5
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer self-update 2.6.5

# Définit le dossier de travail
WORKDIR /var/www/html

# Copie uniquement les fichiers de dépendances PHP pour profiter du cache
# COPY composer.json composer.lock ./

# Installe les dépendances PHP (avec cache Docker optimisé)
# RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-scripts

# Puis copie le reste du code (hors dossiers ignorés)
COPY . .

# Copie le script d'entrypoint et le rend exécutable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Augmente la mémoire PHP à 2G
RUN echo "memory_limit = 2G" > /usr/local/etc/php/conf.d/99-memory-limit.ini

# Expose le port PHP-FPM
EXPOSE 9000

# Déclare l'entrypoint et la commande par défaut
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
