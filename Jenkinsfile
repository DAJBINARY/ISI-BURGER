pipeline {
    agent any

    environment {
        // Nom de l'image Docker à créer
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
                    // Installer les dépendances Laravel
                    sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                    // Copier .env.example vers .env s'il n'existe pas déjà
                    sh 'if [ ! -f .env ]; then cp .env.example .env; fi'
                    // Générer la clé d'application Laravel
                    sh 'php artisan key:generate'
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
