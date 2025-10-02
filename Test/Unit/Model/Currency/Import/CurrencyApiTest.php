<?php
declare(strict_types=1);

namespace HouseOfApis\CurrencyApi\Test\Unit\Model\Currency\Import;

use HouseOfApis\CurrencyApi\Model\Currency\Import\CurrencyApi;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\LaminasClientFactory as HttpClientFactory;
use Magento\Framework\HTTP\LaminasClient;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CurrencyApiTest extends TestCase
{
    private CurrencyApi $currencyApi;
    private MockObject $scopeConfig;
    private MockObject $httpClientFactory;
    private MockObject $httpClient;
    private MockObject $currencyFactory;
    private MockObject $currency;
    private MockObject $productMetadata;

    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->httpClientFactory = $this->createMock(HttpClientFactory::class);
        $this->httpClient = $this->createMock(LaminasClient::class);
        $this->currencyFactory = $this->createMock(CurrencyFactory::class);
        $this->currency = $this->createMock(\Magento\Directory\Model\Currency::class);
        $this->productMetadata = $this->createMock(ProductMetadataInterface::class);

        $this->httpClientFactory->method('create')
            ->willReturn($this->httpClient);

        // Mock all HTTP client methods to return the client itself for chaining
        $this->httpClient->method('setUri')
            ->willReturnSelf();
        $this->httpClient->method('setOptions')
            ->willReturnSelf();
        $this->httpClient->method('setHeaders')
            ->willReturnSelf();
        $this->httpClient->method('setMethod')
            ->willReturnSelf();

        $this->currencyFactory->method('create')
            ->willReturn($this->currency);

        // Mock currency methods
        $this->currency->method('getConfigAllowCurrencies')
            ->willReturn(['USD', 'EUR', 'GBP']);
        
        $this->currency->method('getConfigBaseCurrencies')
            ->willReturn(['USD']);

        // Mock currency save methods (for saveRates)
        $this->currency->method('setId')
            ->willReturnSelf();
        
        $this->currency->method('setRates')
            ->willReturnSelf();
        
        $this->currency->method('save')
            ->willReturnSelf();

        $this->currencyApi = new CurrencyApi(
            $this->currencyFactory,
            $this->scopeConfig,
            $this->httpClientFactory,
            $this->productMetadata
        );
    }

    public function testImportRatesWithValidApiKey(): void
    {
        // Mock API key and timeout - use any() since they can be called in different orders
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturnMap([
                ['currency/houseofapis_currencyapi/api_key', ScopeInterface::SCOPE_STORE, null, 'test-api-key'],
                ['currency/houseofapis_currencyapi/timeout', ScopeInterface::SCOPE_STORE, null, '15'],
            ]);

        // Mock Magento version (can be called multiple times due to retry logic)
        $this->productMetadata->expects($this->any())
            ->method('getVersion')
            ->willReturn('2.4.7');

        // Load valid response fixture
        $validResponse = json_decode(file_get_contents(__DIR__ . '/../../../../Fixtures/valid_response.json'), true);

        // Mock HTTP response
        $mockResponse = $this->createMock(\Laminas\Http\Response::class);
        $mockResponse->method('getBody')
            ->willReturn(json_encode($validResponse));

        $this->httpClient->expects($this->any())
            ->method('send')
            ->willReturn($mockResponse);
            

        // Test the method
        $result = $this->currencyApi->importRates();

        // importRates() returns $this, so test fetchRates() separately
        $this->assertInstanceOf(CurrencyApi::class, $result);
        
        // Test fetchRates() method directly
        $rates = $this->currencyApi->fetchRates();
        $this->assertIsArray($rates);
        $this->assertArrayHasKey('USD', $rates);
        $this->assertEquals(0.85602, $rates['USD']['EUR']);
        $this->assertEquals(0.748346155, $rates['USD']['GBP']);
    }

    public function testImportRatesWithInvalidApiKey(): void
    {
        // Mock empty API key (will be called once in convertBatch for each base currency)
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with('currency/houseofapis_currencyapi/api_key', ScopeInterface::SCOPE_STORE)
            ->willReturn('');

        $result = $this->currencyApi->importRates();

        // importRates() returns $this
        $this->assertInstanceOf(CurrencyApi::class, $result);
        
        // Test fetchRates() method directly for empty result
        $rates = $this->currencyApi->fetchRates();
        $this->assertIsArray($rates);
        $this->assertArrayHasKey('USD', $rates);
        $this->assertEmpty(array_filter($rates['USD'])); // All values should be null

        // Check messages
        $messages = $this->currencyApi->getMessages();
        $this->assertNotEmpty($messages);
        $this->assertStringContainsString('No API Key was specified', $messages[0]->render());
    }

    /**
     * @dataProvider errorResponseProvider
     */
    public function testImportRatesWithApiError(string $fixtureFile, string $expectedMessage): void
    {
        // Mock API key and timeout - use any() since they can be called in different orders
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturnMap([
                ['currency/houseofapis_currencyapi/api_key', ScopeInterface::SCOPE_STORE, null, 'test-api-key'],
                ['currency/houseofapis_currencyapi/timeout', ScopeInterface::SCOPE_STORE, null, '15'],
            ]);

        // Mock Magento version (can be called multiple times due to retry logic)
        $this->productMetadata->expects($this->any())
            ->method('getVersion')
            ->willReturn('2.4.7');

        // Load error response fixture
        $errorResponse = json_decode(file_get_contents(__DIR__ . '/../../../../Fixtures/' . $fixtureFile), true);

        // Mock HTTP error response
        $mockResponse = $this->createMock(\Laminas\Http\Response::class);
        $mockResponse->method('getBody')
            ->willReturn(json_encode($errorResponse));

        $this->httpClient->expects($this->any())
            ->method('send')
            ->willReturn($mockResponse);

        $result = $this->currencyApi->importRates();

        // importRates() returns $this
        $this->assertInstanceOf(CurrencyApi::class, $result);
        
        // Test fetchRates() method directly for empty result
        $rates = $this->currencyApi->fetchRates();
        $this->assertIsArray($rates);
        $this->assertArrayHasKey('USD', $rates);
        $this->assertEmpty(array_filter($rates['USD'])); // All values should be null

        // Check error message
        $messages = $this->currencyApi->getMessages();
        $this->assertNotEmpty($messages);
        $this->assertStringContainsString($expectedMessage, $messages[0]->render());
    }

    public function errorResponseProvider(): array
    {
        return [
            '401 Unauthorized' => ['error_401.json', 'API key is not valid'],
            '400 Bad Request' => ['error_400.json', 'did not supply an API key'],
            '406 Not Acceptable' => ['error_406.json', 'base currency'],
        ];
    }
}
