# Real Time Currency Api Importer

## Table of Contents
1. [Overview](#overview)
2. [Installation](#installation)
3. [API Key](#api-key)
4. [Configuration](#configuration)
5. [Importing Currency Rates](#importing-currency-rates)
6. [Currency List](#currency-list)
7. [Usage](#usage)
8. [Troubleshooting](#troubleshooting)
9. [Support](#support)

---

## Overview

The CurrencyAPI.net extension for Magento 2 provides real-time currency exchange rates from [CurrencyAPI.net](https://currencyapi.net). This extension automatically updates your store's currency rates, ensuring accurate pricing for international customers. A great alternative to Fixer.io.

### Key Features
- **Real-time Exchange Rates**: Get up-to-date currency rates from CurrencyAPI.net
- **152 Currencies**: Support for all major world currencies
- **Automatic Updates**: Currency rates update automatically via Magento's cron
- **Easy Configuration**: Simple setup through Magento Admin
- **Secure**: API key stored securely in Magento configuration
- **Error Handling**: Robust error handling with retry logic

### Requirements
- Magento 2.4.0 or higher
- PHP 8.1 or higher
- Valid CurrencyAPI.net API key

---

## Installation

### Installing via Composer from Magento Marketplace

1. **Get your extension information**:
   - Log in to [Commerce Marketplace](https://marketplace.magento.com) with your account
   - Click **Your name** > **My Profile**
   - Click **My Purchases**
   - Find the extension and note the component name and version

2. **Ensure your `composer.json` includes the Magento repository**:
   ```json
   "repositories": [
       {
           "type": "composer",
           "url": "https://repo.magento.com/"
       }
   ]
   ```

3. **Install the extension with the specific version**:
   ```bash
   composer require houseofapis/magento2-currencyapi-importer:1.0.0
   ```

4. **Enter your authentication keys** when prompted (your public key is your username; your private key is your password)

### Post-Installation Steps

After installing using any method above, run these commands:

```bash
# Enable the module
php bin/magento module:enable HouseOfApis_CurrencyApi

# Update the database schema
php bin/magento setup:upgrade

# Compile dependency injection
php bin/magento setup:di:compile

# Verify the module is enabled
php bin/magento module:status HouseOfApis_CurrencyApi

# Clean the cache
php bin/magento cache:clean
```

> **Note**: If you encounter any issues loading the storefront, run `php bin/magento cache:flush` to completely flush all caches.

---

## API Key

**You need an API key to use this extension.**

Get your API key by creating a free account at [CurrencyAPI.net](https://currencyapi.net):
- Free plan: USD base currency only
- Paid plans: Required to change the base currency (for example to EUR, GBP, etc.)

---

## Configuration

![Add Magento Api Key](https://currencyapi.net/images/magento/magento_api_key.png "Add Api Key")

1. Go to **Stores > Configuration > General > Currency Setup**
2. Select **CurrencyApi.net**
3. Enter your API key from [CurrencyAPI.net](https://currencyapi.net)
4. Configure connection timeout if needed
5. Save configuration

---

## Importing Currency Rates

![Select CurrencyApi.net import service](https://currencyapi.net/images/magento/magento_import.png "Import")

1. Go to **Stores > Currency Rates**
2. Click Import Service and select CurrencyApi.net
3. Click the Import button

![Save Imported Currencies](https://currencyapi.net/images/magento/magento_import_success.png "Save")

1. The new rates will now be imported
2. Click Save Currency Rates to apply

---

## Importing Currency Rates Automatically (via Cron)

To tell Magento to fetch currency rates from CurrencyApi.net automatically, we need to select CurrencyApi.net as the import service

1. Go to **Go to Stores → Configuration → General → Currency Setup**
2. Head to the Scheduled Import Settings section
3. Ensure Enabled is set to Yes
4. Under the Service dropdown, select "CurrencyApi.net"
5. Select the time and frequency you want to update the rates
6. Click Save Config

![Automatic CurrencyApi Updates](https://currencyapi.net/images/magento/magento_select_cron.webp "Automatic CurrencyApi Updates")

---

## Currency List

We supply up to 152 fiat currencies, cryptos and precious metals.

By default, Magento does not support cryptos and precious metals and there maybe some old currencies that are available on Magento but not available from our API. 

For a full list of the currencies we supply, head over to [our currency list](https://currencyapi.net/currency-list/) page.

---

## Usage

### Automatic Rate Updates

The extension automatically updates currency rates via Magento's cron system:

1. **Ensure cron is running** on your server
2. **Rates update** every hour by default
3. **Monitor** the **Stores > Currency Rates** page for updates

### Base Currency Configuration

- **Free Plan**: USD base currency only
- **Paid Plans**: Any supported currency as base
- **Multiple Bases**: Configure multiple base currencies for different store views

---

## Troubleshooting

### Common Issues

#### "No API Key was specified" Error
**Problem**: Extension shows error about missing API key.

**Solution**:
1. Go to **Stores > Configuration > General > Currency Setup**
2. Select "CurrencyApi.net" as import service
3. Enter your API key and save

#### Currency Rates Not Updating
**Problem**: Rates are not updating automatically.

**Solutions**:
1. **Check cron jobs**: Ensure Magento cron is running
2. **Verify API key**: Make sure your API key is valid
3. **Check connectivity**: Ensure your server can reach currencyapi.net
4. **Review logs**: Check `var/log/system.log` for errors

#### "API key is not valid" Error
**Problem**: Extension reports invalid API key.

**Solutions**:
1. **Verify key**: Double-check your API key from CurrencyAPI.net dashboard
2. **Check subscription**: Ensure your account is active
3. **Regenerate key**: Try generating a new API key

#### Import Timeout Errors
**Problem**: Currency import times out.

**Solutions**:
1. **Increase timeout**: Set higher timeout value in configuration
2. **Check server**: Ensure your server has stable internet connection
3. **Contact support**: If issues persist, contact CurrencyAPI.net support

### Debugging

#### Enable Debug Logging
1. **Go to** **Stores > Configuration > Advanced > Developer**
2. **Set** "Log to File" to "Yes"
3. **Check** `var/log/debug.log` for detailed information

#### Check System Logs
1. **Review** `var/log/system.log` for currency-related errors
2. **Look for** entries containing "CurrencyApi" or "currency"

---

## Support

### Getting Help

- **Email**: support@currencyapi.net
- **Website**: [CurrencyAPI.net](https://currencyapi.net)

### Extension Support

- **GitHub Issues**: [Report bugs or request features](https://github.com/houseofapis/magento2-currencyapi-importer/issues)
- **Source Code**: [View source on GitHub](https://github.com/houseofapis/magento2-currencyapi-importer)


---

## License

This extension is licensed under the MIT License. See the LICENSE file for details.

---

## Changelog

### Version 1.0.0
- Initial release
- Support for 152 currencies
- Real-time rate updates
- Magento 2.4+ compatibility
- PHP 8.1+ support

---

*For the most up-to-date information, visit [CurrencyAPI.net](https://currencyapi.net) or check our [GitHub repository](https://github.com/houseofapis/magento2-currencyapi-importer).*
