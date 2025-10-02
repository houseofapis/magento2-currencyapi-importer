.PHONY: help test test-verbose test-coverage test-specific clean build

# Default target
help:
	@echo "Available commands:"
	@echo "  make test          - Build (if needed) and run all unit tests"
	@echo "  make test-verbose  - Build (if needed) and run tests with verbose output"
	@echo "  make test-coverage - Build (if needed) and run tests with coverage report"
	@echo "  make test-specific - Build (if needed) and run a specific test file"
	@echo "  make build         - Build Docker image"
	@echo "  make clean         - Clean up Docker containers and images"

# Run all unit tests (auto-build then run)
test:
	docker compose build magento-currencyapi-tests
	docker compose run --rm -e CMD="vendor/bin/phpunit Test/Unit/ --verbose" magento-currencyapi-tests

# Run tests with coverage report
test-coverage:
	docker compose build magento-currencyapi-tests
	docker compose run --rm -e CMD="vendor/bin/phpunit Test/Unit/ --coverage-html coverage/" magento-currencyapi-tests
	@echo "Coverage report generated in coverage/ directory"

# Run specific test file
test-specific:
	docker compose build magento-currencyapi-tests
	docker compose run --rm -e CMD="vendor/bin/phpunit Test/Unit/Model/Currency/Import/CurrencyApiTest.php --verbose" magento-currencyapi-tests

# Build Docker image
build:
	docker compose build magento-currencyapi-tests

# Clean up Docker resources
clean:
	docker compose down --rmi all --volumes --remove-orphans
	docker system prune -f

