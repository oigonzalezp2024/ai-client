<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\AI\AIPromptProcessor;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Dotenv\Dotenv; // Keep Dotenv for mocking purposes for constructor
use Exception;
use Mockery;

class AIPromptProcessorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Mockery::close(); // Always close mocks for a clean state
        // It's still good practice to clean $_ENV/$_SERVER for robust isolation,
        // even if the class no longer directly reads them.
        unset($_ENV['API_KEY'], $_SERVER['API_KEY']);
        unset($_ENV['API_BASE_URI'], $_SERVER['API_BASE_URI']);
        unset($_ENV['AI_MODEL'], $_SERVER['AI_MODEL']);
        putenv('API_KEY');
        putenv('API_BASE_URI');
        putenv('AI_MODEL');
    }

    protected function tearDown(): void
    {
        Mockery::close(); // Always close mocks at the end
        unset($_ENV['API_KEY'], $_SERVER['API_KEY']);
        unset($_ENV['API_BASE_URI'], $_SERVER['API_BASE_URI']);
        unset($_ENV['AI_MODEL'], $_SERVER['AI_MODEL']);
        putenv('API_KEY');
        putenv('API_BASE_URI');
        putenv('AI_MODEL');
        parent::tearDown();
    }

    /**
     * Helper to create an AIPromptProcessor with a mocked Guzzle client.
     * The constructor parameters for AIPromptProcessor are now passed directly.
     *
     * @param string $apiKey
     * @param string $baseUri
     * @param string $model
     * @param array $responses Array of GuzzleHttp\Psr7\Response or GuzzleHttp\Exception\RequestException.
     * @return AIPromptProcessor
     * @throws \ReflectionException
     */
    private function createProcessorWithMockedClient(
        string $apiKey,
        string $baseUri,
        string $model,
        array $responses
    ): AIPromptProcessor
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $mockClient = new Client(['handler' => $handlerStack]);

        // AIPromptProcessor's constructor now requires these parameters
        $processor = new AIPromptProcessor($apiKey, $baseUri, $model);

        // Use Reflection to inject the mocked Guzzle client.
        $reflection = new \ReflectionClass($processor);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($processor, $mockClient);

        return $processor;
    }

    /**
     * Test to verify that getAIResponse returns the expected response
     * when the API returns valid content.
     */
    public function testGetAIResponseReturnsExpectedTextOnSuccess(): void
    {
        // Define the environment variables explicitly for this test
        $apiKey = 'test_api_key_success';
        $baseUri = 'http://test-api.com/';
        $model = 'test-model';

        $expectedText = "La respuesta de la IA.";
        $mockResponseContent = json_encode([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => $expectedText]
                        ]
                    ]
                ]
            ]
        ]);

        $mockResponses = [
            new Response(200, [], $mockResponseContent)
        ];

        // Pass the variables to the helper function's constructor arguments
        $processor = $this->createProcessorWithMockedClient($apiKey, $baseUri, $model, $mockResponses);

        $prompt = "Hola IA, ¿cómo estás?";
        $result = $processor->getAIResponse($prompt);

        $this->assertEquals($expectedText, $result);
    }

    /**
     * Test to verify that an exception is thrown when the API
     * does not return valid content (e.g., `candidates` or `text` missing).
     */
    public function testGetAIResponseThrowsExceptionOnInvalidApiResponse(): void
    {
        // Define the environment variables explicitly for this test
        $apiKey = 'test_api_key_invalid';
        $baseUri = 'http://test-api.com/';
        $model = 'test-model';

        $mockResponseContent = json_encode([
            'some_other_data' => 'value' // Invalid JSON content for the expected structure
        ]);

        $mockResponses = [
            new Response(200, [], $mockResponseContent)
        ];

        // Pass the variables to the helper function's constructor arguments
        $processor = $this->createProcessorWithMockedClient($apiKey, $baseUri, $model, $mockResponses);

        $prompt = "Generar texto incompleto.";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("La API no devolvió contenido válido o en el formato esperado.");

        $processor->getAIResponse($prompt);
    }

    /**
     * Test to verify that an exception is thrown when Guzzle
     * throws a RequestException (network issues, 4xx, 5xx).
     */
    public function testGetAIResponseThrowsExceptionOnRequestException(): void
    {
        // Define the environment variables explicitly for this test
        $apiKey = 'test_api_key_request_error';
        $baseUri = 'http://test-api.com/';
        $model = 'test-model';

        $mockResponses = [
            new RequestException("Error de conexión simulado", new \GuzzleHttp\Psr7\Request('POST', 'test-url'))
        ];

        // Pass the variables to the helper function's constructor arguments
        $processor = $this->createProcessorWithMockedClient($apiKey, $baseUri, $model, $mockResponses);

        $prompt = "Texto para error de red.";
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Error en la solicitud a la API:/');

        $processor->getAIResponse($prompt);
    }

    /**
     * Test to verify that the constructor throws an exception when API_KEY is missing.
     */
    public function testConstructorThrowsExceptionWhenApiKeyIsMissing(): void
    {
        // Pass empty string for API_KEY to simulate missing
        $apiKey = ''; // This will trigger the exception
        $baseUri = 'http://test-api.com/';
        $model = 'test-model';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Las siguientes variables de entorno para la API no están configuradas: API_KEY");

        // Instantiate AIPromptProcessor directly, passing the variables.
        new AIPromptProcessor($apiKey, $baseUri, $model);
    }

    /**
     * Test to verify that the constructor throws an exception when API_BASE_URI is missing.
     */
    public function testConstructorThrowsExceptionWhenBaseUriIsMissing(): void
    {
        $apiKey = 'test_api_key';
        $baseUri = ''; // This will trigger the exception
        $model = 'test-model';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Las siguientes variables de entorno para la API no están configuradas: API_BASE_URI");

        new AIPromptProcessor($apiKey, $baseUri, $model);
    }

    /**
     * Test to verify that the constructor throws an exception when AI_MODEL is missing.
     */
    public function testConstructorThrowsExceptionWhenModelIsMissing(): void
    {
        $apiKey = 'test_api_key';
        $baseUri = 'http://test-api.com/';
        $model = ''; // This will trigger the exception

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Las siguientes variables de entorno para la API no están configuradas: AI_MODEL");

        new AIPromptProcessor($apiKey, $baseUri, $model);
    }

    /**
     * Test to verify that the constructor throws an exception when all variables are missing.
     */
    public function testConstructorThrowsExceptionWhenAllEnvVariablesAreMissing(): void
    {
        $apiKey = '';
        $baseUri = '';
        $model = '';

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Las siguientes variables de entorno para la API no están configuradas: API_KEY, API_BASE_URI, AI_MODEL");

        new AIPromptProcessor($apiKey, $baseUri, $model);
    }
}
