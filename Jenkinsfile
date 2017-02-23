pipeline {
    agent {
        dockerfile {}
    }
    options {
        // Keep the 10 most recent builds
        buildDiscarder(logRotator(numToKeepStr:'10'))
    }
    stages {
        stage ('Install & Build') {
            steps {
                sh "HOME=$WORKSPACE composer install -d $WORKSPACE"
            }
        }
        stage ('Test') {
            steps {
                sh 'php -dzend_extension=xdebug.so vendor/bin/codecept run unit --coverage --coverage-html'
            }
        }
        stage('Metrics') {
            steps {
                sh 'test -f vendor/bin/phpmetrics || composer require phpmetrics/phpmetrics'
                sh './vendor/bin/phpmetrics --report-html=tests/_output/phpmetrics.html src'
            }
        }

        stage('Reports') {
            steps {
                publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'tests/_output/coverage', reportFiles: 'index.html', reportName: 'HTML Report - Coverage'])
                publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'tests/_output', reportFiles: 'phpmetrics.html', reportName: 'HTML Report - PHPMetrics'])
            }
        }
        stage('Clean Up') {
            steps {
                deleteDir()
            }
        }
    }
}
