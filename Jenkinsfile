pipeline {
    agent {
        docker {
            image 'composer:latest'  // Image avec PHP et Composer préinstallés
            args '-u root'  // Exécute en tant que root pour éviter les problèmes de permissions
        }
    }

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
                    // Vérifier et installer les dépendances Laravel
                    sh '''
                    composer install --no-interaction --prefer-dist --optimize-autoloader
                    if [ ! -f .env ]; then cp .env.example .env; fi
                    php artisan key:generate
                    '''
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
            echo '✅ Pipeline terminé avec succès !'
        }
        failure {
            echo '❌ Le pipeline a échoué.'
        }
        always {
            echo 'ℹ️ Pipeline terminé.'
        }
    }
}
