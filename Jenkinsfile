node {
    stage 'Prepare environment'

    checkout scm

    sh 'docker -v'

    docker.image('laradock/workspace').inside {

        stage('Build') {
            sh "HOME=$WORKSPACE composer install -d $WORKSPACE"
        }

        stage('Test') {
           sh './vendor/bin/codecept run unit --coverage --coverage-html'
        }

        stage('Metrics') {
            echo('Generating Metrics')
            sh 'test -f vendor/bin/phpmetrics || composer require phpmetrics/phpmetrics'
            sh './vendor/bin/phpmetrics --report-html=tests/_output/phpmetrics.html src'
        }

        stage('Reports') {
            echo('Generating Reports')
            publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'tests/_output/coverage', reportFiles: 'index.html', reportName: 'HTML Report - Coverage'])
            publishHTML([allowMissing: false, alwaysLinkToLastBuild: false, keepAll: false, reportDir: 'tests/_output', reportFiles: 'phpmetrics.html', reportName: 'HTML Report - PHPMetrics'])
        }
    }
}
