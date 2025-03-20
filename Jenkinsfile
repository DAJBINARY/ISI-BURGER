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
                    // Vérifier et installer PHP si absent
                    sh '''
                    if ! command -v php &> /dev/null; then
                        echo "PHP non trouvé, installation en cours..."
                        apt-get update && apt-get install -y php-cli php-mbstring unzip curl
                    else
                        echo "PHP est déjà installé."
                    fi
                    '''

                    // Vérifier et installer Composer si absent
                    sh '''
                    if ! command -v composer &> /dev/null; then
                        echo "Composer non trouvé, installation en cours..."
                        curl -sS https://getcomposer.org/installer | php
                        mv composer.phar /usr/local/bin/composer
                    else
                        echo "Composer est déjà installé."
                    fi
                    '''

                    // Installer les dépendances Laravel (ignore l'erreur si une dépendance manque)
                    catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                        sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                    }

                    // Copier .env.example vers .env s'il n'existe pas déjà
                    sh '''
                    if [ ! -f .env ]; then
                        cp .env.example .env
                    fi
                    '''

                    // Générer la clé d'application Laravel
                    sh 'php artisan key:generate'
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
            echo ' Pipeline terminé avec succès !'
        }
        failure {
            echo ' Le pipeline a échoué.'
        }
        always {
            echo '  Pipeline terminé.'
        }
    }
}
