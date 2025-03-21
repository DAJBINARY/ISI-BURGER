pipeline {
    agent any

    environment {
        // Nom de l'image Docker à créer
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
                    // Installer les dépendances Laravel directement dans le conteneur Jenkins
                    sh '''
                    # Exécuter Composer dans le conteneur Jenkins (en utilisant Docker monté)
                    docker exec jenkins /bin/bash -c "composer install --no-interaction --prefer-dist --optimize-autoloader"

                    # Copier .env.example vers .env s'il n'existe pas déjà
                    docker exec jenkins /bin/bash -c "if [ ! -f /var/jenkins_home/workspace/ISI-Burger/.env ]; then cp /var/jenkins_home/workspace/ISI-Burger/.env.example /var/jenkins_home/workspace/ISI-Burger/.env; fi"

                    # Générer la clé d'application Laravel
                    docker exec jenkins /bin/bash -c "php /var/jenkins_home/workspace/ISI-Burger/artisan key:generate"
                    '''
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Construire l'image Docker du projet
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
