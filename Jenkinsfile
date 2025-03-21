pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "DAJBINARY/isi-burger:latest"
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    // Exécuter Composer dans le conteneur Jenkins (en utilisant Docker monté)
                    sh 'docker exec jenkins-with-php-composer-container /bin/bash -c "composer install --no-interaction --prefer-dist --optimize-autoloader"'

                    // Copier .env.example vers .env s'il n'existe pas déjà
                    sh 'docker exec jenkins-with-php-composer-container /bin/bash -c "if [ ! -f /var/jenkins_home/workspace/ISI-Burger/.env ]; then cp /var/jenkins_home/workspace/ISI-Burger/.env.example /var/jenkins_home/workspace/ISI-Burger/.env; fi"'

                    // Générer la clé d'application Laravel
                    sh 'docker exec jenkins-with-php-composer-container /bin/bash -c "php /var/jenkins_home/workspace/ISI-Burger/artisan key:generate"'
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    sh "docker build -t ${DOCKER_IMAGE} ."
                }
            }
        }
    }

    post {
        success {
            echo 'Pipeline terminé avec succès !'
        }
        failure {
            echo 'Le pipeline a échoué.'
        }
        always {
            echo 'Pipeline terminé.'
        }
    }
}
