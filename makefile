all:
	@echo try make coverage or make serve-coverage or serve-metrics
serve-coverage: FRC
	php -S 127.0.0.1:8888 -t tests/_output/coverage
coverage: FRC
	#phpunit --coverage-html=build/output/coverage tests
	codecept run  unit --coverage --coverage-xml --coverage-html
serve-metrics: FRC
	php -S 127.0.0.1:8888 build/output/report.html
phpmetrics: FRC
	phpmetrics --report-html=build/output/report.html src/
FRC:
