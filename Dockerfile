FROM php:8.2-apache

# Installer les dépendances système nécessaires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
        zip \
        unzip \
        git \
        libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP requises
RUN docker-php-ext-install pdo pdo_pgsql zip

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Changer le DocumentRoot vers le dossier public de Laravel
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copier Composer depuis l'image officielle
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code du projet dans le conteneur
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
