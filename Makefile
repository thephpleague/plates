.PHONY: test test-coverage test-coverage-clover

test:
	./vendor/bin/peridot test
test-coverage:
	./vendor/bin/peridot --code-coverage-path=build/coverage -r spec -r html-code-coverage test
test-coverage-clover:
	./vendor/bin/peridot --code-coverage-path=build/coverage -r spec -r clover-code-coverage test
