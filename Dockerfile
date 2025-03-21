FROM jenkins/jenkins:lts

USER root
RUN apt-get update && apt-get install -y php curl

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

USER jenkins
