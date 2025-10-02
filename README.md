# HouseOfApis CurrencyAPI for Magento 2

A Magento 2 extension that integrates with CurrencyAPI.net to provide real-time currency exchange rates.

## Features

- Real-time currency exchange rates from CurrencyAPI.net
- Easy configuration through Magento admin
- Support for all major currencies
- Automatic rate updates
- Secure API key storage

## Installation

### Via Composer (Recommended)

```bash
composer require houseofapis/module-currencyapi
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
3. Enter your API key from [CurrencyAPI.net](https://currencyapi.net)
4. Configure connection timeout if needed
5. Save configuration

## API Key

Get your free API key from [CurrencyAPI.net](https://currencyapi.net):
- Free plan: USD base currency only
- Paid plans: Support for custom base currencies

## Requirements

- Magento 2.4.x
- PHP 8.1+
- Valid CurrencyAPI.net API key

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

### Local Development

If you have PHP 8.1+ installed locally:

```bash
# Install dependencies
composer install

# Run tests locally
make test-local
```

### Test Structure

- `Test/Unit/` - Unit tests with mocked dependencies
- Tests cover API integration, error handling, and configuration parsing

## Support

For support and questions, please contact:
- Email: support@houseofapis.com
- Website: [CurrencyAPI.net](https://currencyapi.net)

## License

MIT License - see LICENSE file for details.
