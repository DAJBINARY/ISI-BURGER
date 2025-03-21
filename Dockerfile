# Utilise l'image officielle de Jenkins LTS
FROM jenkins/jenkins:lts

# Passer à l'utilisateur root pour effectuer les installations
USER root

# Mettre à jour et installer Docker et Composer
RUN apt-get update && \
    apt-get install -y \
    curl \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-json \
    docker.io && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Revenir à l'utilisateur jenkins
USER jenkins
