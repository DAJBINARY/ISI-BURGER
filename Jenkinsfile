pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "DAJBINARY/isi-burger:latest"
    }

    stages {
        stage('Checkout') {
            steps {
                // Utilise le SCM configuré dans Jenkins
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    // Utilisation de Composer et PHP directement depuis le conteneur
                    sh 'docker exec jenkins /bin/bash -c "composer install --no-interaction --prefer-dist --optimize-autoloader"'

                    // Copier .env.example vers .env s'il n'existe pas déjà
                    sh 'docker exec jenkins /bin/bash -c "if [ ! -f .env ]; then cp .env.example .env; fi"'

                    // Générer la clé d'application Laravel
                    sh 'docker exec jenkins /bin/bash -c "php artisan key:generate"'
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
