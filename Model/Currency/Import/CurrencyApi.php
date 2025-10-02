<?php
declare(strict_types=1);

namespace HouseOfApis\CurrencyApi\Model\Currency\Import;

use Throwable;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\Currency\Import\ImportInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\HTTP\LaminasClient;
use Magento\Framework\HTTP\LaminasClientFactory as HttpClientFactory;
use Magento\Store\Model\ScopeInterface;

class CurrencyApi implements ImportInterface
{
    private const API_HOST = 'https://currencyapi.net';
    private const API_PATH = '/api/v1/rates?key={{API_KEY}}&base={{CURRENCY_FROM}}';

    private array $messages = [];
    private HttpClientFactory $httpClientFactory;
    private CurrencyFactory $currencyFactory;
    private ScopeConfigInterface $scopeConfig;
    private ProductMetadataInterface $productMetadata;

    public function __construct(
        CurrencyFactory $currencyFactory,
        ScopeConfigInterface $scopeConfig,
        HttpClientFactory $httpClientFactory,
        ProductMetadataInterface $productMetadata,
    ) {
        $this->currencyFactory = $currencyFactory;
        $this->scopeConfig = $scopeConfig;
        $this->httpClientFactory = $httpClientFactory;
        $this->productMetadata = $productMetadata;
    }

    public function importRates()
    {
        $data = $this->fetchRates();
        $this->saveRates($data);
        return $this;
    }

    public function fetchRates()
    {
        $data = [];
        $currencies = $this->getCurrencyCodes();
        $defaultCurrencies = $this->getDefaultCurrencyCodes();

        foreach ($defaultCurrencies as $currencyFrom) {
            if (!isset($data[$currencyFrom])) {
                $data[$currencyFrom] = [];
            }
            $data = $this->convertBatch($data, $currencyFrom, $currencies);
            ksort($data[$currencyFrom]);
        }
        return $data;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    private function convertBatch(array $data, string $currencyFrom, array $currenciesTo): array
    {
        $apiKey = (string)$this->scopeConfig->getValue(
            'currency/houseofapis_currencyapi/api_key',
            ScopeInterface::SCOPE_STORE
        );
        
        if ($apiKey === '') {
            $this->messages[] = __('CurrencyApi.net - No API Key was specified.');
            $data[$currencyFrom] = $this->makeEmptyResponse($currenciesTo);
            return $data;
        }

        $url = str_replace(
            ['{{API_KEY}}', '{{CURRENCY_FROM}}'],
            [$apiKey, $currencyFrom],
            self::API_HOST . self::API_PATH
        );

        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        set_time_limit(0);
        try {
            $response = $this->getServiceResponse($url);
        } finally {
            ini_restore('max_execution_time');
        }

        if (!$this->validateResponse($response, $currencyFrom)) {
            $data[$currencyFrom] = $this->makeEmptyResponse($currenciesTo);
            return $data;
        }

        foreach ($currenciesTo as $currencyTo) {
            if ($currencyFrom === $currencyTo) {
                $data[$currencyFrom][$currencyTo] = 1;
                continue;
            }
            if (empty($response['rates'][$currencyTo])) {
                // For missing currencies, set rate to 1 (same as base)
                $this->messages[] = __('CurrencyApi.net - Currency %1 not supported, using base rate of 1.', $currencyTo);
                $data[$currencyFrom][$currencyTo] = 1;
                continue;
            }
            $data[$currencyFrom][$currencyTo] = (float)$response['rates'][$currencyTo];
        }

        return $data;
    }

    private function saveRates(array $rates)
    {
        foreach ($rates as $currencyCode => $currencyRates) {
            $this->currencyFactory->create()->setId($currencyCode)->setRates($currencyRates)->save();
        }
        return $this;
    }

    private function getServiceResponse(string $url, int $retry = 0): array
    {
        /** @var LaminasClient $httpClient */
        $httpClient = $this->httpClientFactory->create();
        $response = [];

        try {
            $httpClient->setUri($url);
            $httpClient->setOptions(
                [
                    'timeout' => $this->scopeConfig->getValue(
                        'currency/houseofapis_currencyapi/timeout',
                        ScopeInterface::SCOPE_STORE
                    ),
                ]
            );
            $httpClient->setHeaders([
                'X-Magento-Version' => (string)($this->productMetadata->getVersion() ?? '2'),
            ]);
            $httpClient->setMethod('GET');
            $json = $httpClient->send()->getBody();
            $response = json_decode($json, true) ?: [];
        } catch (Throwable $e) {
            if ($retry === 0) {
                $response = $this->getServiceResponse($url, 1);
            }
        }
        return $response;
    }

    private function makeEmptyResponse(array $currenciesTo): array
    {
        return array_fill_keys($currenciesTo, null);
    }

    private function validateResponse(array $response, string $baseCurrency): bool
    {
        if (isset($response['valid']) && $response['valid'] === true && isset($response['rates']) && is_array($response['rates'])) {
            return true;
        }

        $errorCodes = [
            400 => __('CurrencyApi.net - You did not supply an API key.'),
            401 => __('CurrencyApi.net - Your API key is not valid.'),
            406 => __('CurrencyApi.net - The requested base currency "%1" does not exist on our service.', $baseCurrency),
            408 => __('CurrencyApi.net - Your subscription plan does not allow you to select base currency "%1". Please upgrade your plan.', $baseCurrency),
            421 => __('CurrencyApi.net - Your IP address is blacklisted. Please contact support to be removed from the blacklist.'),
            500 => __('CurrencyApi.net - An error occurred on our end. Please try again later.'),
        ];

        $errorCode = isset($response['error']['code']) ? $response['error']['code'] : null;
        $this->messages[] = $errorCodes[$errorCode] ?? __('CurrencyApi.net - Currency rates can\'t be retrieved.');
        return false;
    }

    private function getCurrencyCodes()
    {
        return $this->currencyFactory->create()->getConfigAllowCurrencies();
    }

    private function getDefaultCurrencyCodes()
    {
        return $this->currencyFactory->create()->getConfigBaseCurrencies();
    }
}
