.PHONY: help test test-verbose test-coverage test-specific clean build phpcs phpcs-report

# Default target
help:
	@echo "Available commands:"
	@echo "  make test          - Build (if needed) and run all unit tests"
	@echo "  make test-verbose  - Build (if needed) and run tests with verbose output"
	@echo "  make test-coverage - Build (if needed) and run tests with coverage report"
	@echo "  make test-specific - Build (if needed) and run a specific test file"
	@echo "  make build         - Build Docker image"
	@echo "  make clean         - Clean up Docker containers and images"
	@echo "  make phpcs         - Run Magento Coding Standard checks"
	@echo "  make phpcs-report  - Run PHPCS and output JSON report to report/phpcs.json"

# Run all unit tests (auto-build then run)
test:
	docker compose build magento-currencyapi-tests
	docker compose run --rm magento-currencyapi-tests vendor/bin/phpunit Test/Unit/ --verbose

# Run tests with coverage report
test-coverage:
	docker compose build magento-currencyapi-tests
	docker compose run --rm magento-currencyapi-tests vendor/bin/phpunit Test/Unit/ --coverage-html coverage/
	@echo "Coverage report generated in coverage/ directory"

# Run specific test file
test-specific:
	docker compose build magento-currencyapi-tests
	docker compose run --rm magento-currencyapi-tests vendor/bin/phpunit Test/Unit/Model/Currency/Import/CurrencyApiTest.php --verbose

# Build Docker image
build:
	docker compose build magento-currencyapi-tests

# Clean up Docker resources
clean:
	docker compose down --rmi all --volumes --remove-orphans
	docker system prune -f

# Run PHPCS (Magento Coding Standard)
phpcs:
	docker compose build magento-currencyapi-tests
	docker compose run --rm magento-currencyapi-tests sh -lc "vendor/bin/phpcs --config-set installed_paths vendor/magento/magento-coding-standard,vendor/magento/php-compatibility-fork && vendor/bin/phpcs --standard=Magento2 --extensions=php,phtml --error-severity=10 --ignore-annotations etc Model registration.php"

# Run PHPCS and write JSON report
phpcs-report:
	docker compose build magento-currencyapi-tests
	docker compose run --rm magento-currencyapi-tests sh -lc "mkdir -p report && vendor/bin/phpcs --config-set installed_paths vendor/magento/magento-coding-standard,vendor/magento/php-compatibility-fork && vendor/bin/phpcs --standard=Magento2 --extensions=php,phtml --error-severity=10 --ignore-annotations --report=json --report-file=report/phpcs.json etc Model registration.php"

clean-zip:
	rm -rf build CurrencyApi.zip

# Build a Marketplace-ready zip
build-zip: clean-zip
	git archive --format=zip -o CurrencyApi.zip HEAD 

