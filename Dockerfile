# Utiliser une image PHP avec Apache
FROM php:8.2-apache

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier uniquement le fichier composer.json et composer.lock pour optimiser le cache Docker
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Copier le reste du projet
COPY . /var/www/html

# Configurer les permissions en une seule commande
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
