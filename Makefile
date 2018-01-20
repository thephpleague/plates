.PHONY: test test-coverage test-coverage-clover serve-generated-docs deploy-docs docs

test:
	./vendor/bin/peridot test
test-coverage:
	./vendor/bin/peridot --code-coverage-path=build/coverage -r spec -r html-code-coverage test
test-coverage-clover:
	./vendor/bin/peridot --code-coverage-path=build/coverage -r spec -r clover-code-coverage test
docs:
	php scripts/build-docs.php docs-config.json
serve-generated-docs:
	php -S 127.0.0.1:4000 -t .generated
deploy-docs:
	git checkout gh-pages
	cp -r .generated/* .
	git add -A; git commit -sm 'Deploying docs for Plates'
