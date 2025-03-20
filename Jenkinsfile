pipeline {
    agent {
        docker {
            image 'composer:latest'  // Utilise une image Docker avec PHP et Composer préinstallés
            args '-u root'  // Exécute en tant que root pour éviter les problèmes de permissions
        }
    }

    environment {
        DOCKER_IMAGE = "DAJBINARY/isi-burger:latest"
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm  // Récupère le code du référentiel source configuré dans Jenkins
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    // Étape d'installation des dépendances de Laravel
                    echo " Installation des dépendances Laravel..."
                    sh '''
                    # Assurez-vous que les répertoires ont les bonnes permissions
                    mkdir -p vendor bootstrap/cache
                    chmod -R 777 vendor bootstrap/cache

                    # Installe les dépendances Laravel avec Composer
                    composer install --no-interaction --prefer-dist --optimize-autoloader
                    
                    # Copie .env.example vers .env si .env n'existe pas
                    if [ ! -f .env ]; then cp .env.example .env; fi
                    
                    # Génère la clé d'application Laravel
                    php artisan key:generate
                    '''
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Étape de construction de l'image Docker
                    echo "🛠 Construction de l'image Docker..."
                    sh "docker build --progress=plain -t ${DOCKER_IMAGE} ."
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
            sh 'docker system prune -f'  // Nettoyage des ressources Docker
        }
        always {
            echo 'ℹPipeline terminé.'
        }
    }
}
