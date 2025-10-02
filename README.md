# CurrencyApi.net for Magento 2

A Magento 2 extension that integrates with CurrencyApi.net to provide real-time currency exchange rates.

## Features

- Real-time currency exchange rates from CurrencyApi.net
- Easy configuration through Magento admin
- Support for all major currencies (160+ currencies)
- Automatic rate updates via cron
- Secure API key storage
- Error handling and retry logic
- Comprehensive unit test coverage
- Docker-based development environment

## Installation

### Via Composer (Recommended)

```bash
composer require houseofapis/magento2-currencyapi-importer
```

### Manual Installation

1. Download the extension files
2. Copy to `app/code/HouseOfApis/CurrencyApi/`
3. Run the following commands:
   ```bash
   php bin/magento module:enable HouseOfApis_CurrencyApi
   php bin/magento setup:upgrade
   php bin/magento cache:flush
   ```

## Configuration

1. Go to **Stores > Configuration > Currency > Currency Rates**
2. Select **CurrencyApi.net** from the "Import Service" dropdown
3. Enter your API key from [CurrencyApi.net](https://currencyapi.net)
4. Configure connection timeout if needed
5. Save configuration

## API Key

Get your free API key from [CurrencyApi.net](https://currencyapi.net):
- Free plan: USD base currency only
- Paid plans: Support for custom base currencies

## Requirements

- Magento 2.4.0 or higher
- PHP 8.1 or higher
- Valid CurrencyApi.net API key
- cURL extension enabled

## Magento Version Compatibility

| Magento Version | Extension Version | Status |
|----------------|-------------------|---------|
| 2.4.0 - 2.4.7  | 1.0.0            | ✅ Supported |
| 2.4.8+         | 1.0.0            | ✅ Supported |

## Development

### Running Tests

This extension includes comprehensive unit tests. You can run them using Docker:

```bash
# Run all tests
make test

# Run tests with verbose output
make test-verbose

# Run tests with coverage report
make test-coverage

# Run specific test file
make test-specific

# Clean up Docker resources
make clean
```

### Test Structure

- `Test/Unit/` - Unit tests with mocked dependencies
- `Test/Fixtures/` - JSON fixtures for API responses
- `Test/Stubs/` - Magento interface stubs for testing
- Tests cover API integration, error handling, and configuration parsing

### Project Structure

```
├── Model/Currency/Import/
│   └── CurrencyApi.php          # Main import service
├── Test/
│   ├── Unit/                    # Unit tests
│   ├── Fixtures/                # Test data
│   └── Stubs/                   # Magento stubs
├── composer.json                # Package definition
├── registration.php             # Magento module registration
└── README.md                    # This file
```

## Troubleshooting

### Common Issues

**Currency rates not updating:**
- Verify your API key is correct
- Check if cron jobs are running
- Ensure your server can reach currencyapi.net

**"No API Key was specified" error:**
- Go to Stores > Configuration > Currency > Currency Rates
- Select "CurrencyApi.net" as import service
- Enter your API key and save

**Test failures:**
- Run `make test` to verify all tests pass
- Check Docker is running if using containerized tests

## Support

For support and questions, please contact:
- Email: support@currencyapi.net
- Website: [CurrencyApi.net](https://currencyapi.net)

## License

MIT License - see LICENSE file for details.
