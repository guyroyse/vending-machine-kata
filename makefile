all:
	@echo try make coverage, metrics, reports, serve, serve-coverage, serve-metrics
serve-coverage: FRC
	php -S 127.0.0.1:8888 -t tests/_output/coverage
coverage: FRC
	#phpunit --coverage-html=build/output/coverage tests
	codecept run unit --coverage-html
serve: FRC
	php -S 127.0.0.1:8888 -t build/output
serve-metrics: FRC
	php -S 127.0.0.1:8888 build/output/report.html
metrics: FRC
	phpmetrics --report-html=build/output/report.html src/
pmd: FRC
	phpmd src html cleancode,codesize,controversial,design,naming,unusedcode > build/output/pmd.html
cpd: FRC
	phpcpd src > build/output/cpd.txt
loc: FRC
	phploc src > build/output/loc.txt
cs: FRC
	phpcs --standard=psr2 --report=full --report-file=build/output/cs.txt src
reports: FRC
	make -i pmd cpd loc cs
FRC:
