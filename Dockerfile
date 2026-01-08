FROM php:8.1-apache

# Installer les dépendances système nécessaires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
        zip \
        unzip \
        git \
        libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Configurer et installer les extensions PHP requises
RUN docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install pdo pdo_pgsql zip

# Copier Composer depuis l'image officielle Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le projet dans l'image
COPY . /var/www/html/

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Configurer les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]