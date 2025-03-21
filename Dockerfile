# Utiliser l'image officielle de Jenkins LTS
FROM jenkins/jenkins:lts

# Passer à l'utilisateur root pour effectuer les installations
USER root

# Mettre à jour et installer Docker, PHP, Composer, et autres dépendances nécessaires
RUN apt-get update && \
    apt-get install -y \
    curl \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-json \
    ca-certificates \
    gnupg \
    lsb-release \
    sudo && \
    # Ajouter Docker repository et installer Docker
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo tee /etc/apt/trusted.gpg.d/docker.asc && \
    echo "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list && \
    apt-get update && \
    apt-get install -y docker-ce && \
    # Installer Composer
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Revenir à l'utilisateur jenkins
USER jenkins
