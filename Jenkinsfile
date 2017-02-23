pipeline {
    agent {
        dockerfile {}
    }
    environment {
        HOME = $WORKSPACE
    }
    options {
        // Keep the 10 most recent builds
        buildDiscarder(logRotator(numToKeepStr:'10'))
    }
    stages {
        stage ('Install & Build') {
            steps {
                sh "HOME=$HOME WORKSPACE=$WORKSPACE"
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
    }
    post {
        always {
            publishHTML target:[
                allowMissing: false,
                alwaysLinkToLastBuild: false,
                keepAll: false,
                reportDir: 'tests/_output/coverage',
                reportFiles: 'index.html',
                reportName: 'HTML Report - Coverage'
            ]
            publishHTML target:[
                allowMissing: false,
                alwaysLinkToLastBuild: false,
                keepAll: false,
                reportDir: 'tests/_output',
                reportFiles: 'phpmetrics.html',
                reportName: 'HTML Report - PHPMetrics'
            ]
            deleteDir()
        }
    }
}
