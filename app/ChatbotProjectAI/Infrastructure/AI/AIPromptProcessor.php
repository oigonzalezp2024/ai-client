<?php

namespace App\Chatbot\Infrastructure\AI;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
// Ya no necesitamos Dotenv aquí, se encargará el archivo principal.
// use Dotenv\Dotenv;
use Exception;

class AIPromptProcessor
{
    private ?Client $client = null;
    private ?string $apiKey = null;
    private ?string $baseUri = null;
    private ?string $model = null;
    // Ya no necesitamos la propiedad $dotenv

    // El constructor ahora recibe las variables ya cargadas
    public function __construct(string $apiKey, string $baseUri, string $model)
    {
        $this->apiKey = $apiKey;
        $this->baseUri = $baseUri;
        $this->model = $model;

        // La validación se mantiene aquí, pero ahora se lanza una excepción
        // si los valores pasados al constructor son vacíos.
        if (empty($this->apiKey) || empty($this->baseUri) || empty($this->model)) {
            $missingVars = [];
            if (empty($this->apiKey)) $missingVars[] = 'API_KEY';
            if (empty($this->baseUri)) $missingVars[] = 'API_BASE_URI';
            if (empty($this->model)) $missingVars[] = 'AI_MODEL';

            throw new Exception("Las siguientes variables de entorno para la API no están configuradas: " . implode(', ', $missingVars));
        }

        $this->configureHttpClient();
    }

    // El método loadEnvironmentVariables ya no es necesario,
    // ya que las variables se pasan al constructor.
    /*
    private function loadEnvironmentVariables(): void
    {
        // ... (código anterior)
    }
    */

    /**
     * Configures the HTTP client with base parameters.
     * @throws Exception If any necessary property for the client is null (though already validated in constructor).
     */
    private function configureHttpClient(): void
    {
        // Estas comprobaciones aquí son un poco redundantes si la validación del constructor funciona,
        // pero pueden servir como una última defensa.
        if (is_null($this->baseUri) || is_null($this->apiKey)) {
             throw new Exception("Configuración del cliente HTTP incompleta. Base URI o API Key son nulos.");
        }

        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'key' => $this->apiKey,
            ],
        ]);
    }

    /**
     * Sends a string prompt to the AI API and returns the generated response as a string.
     * This is the sole public responsibility of the class.
     *
     * @param string $prompt The text prompt to send to the AI.
     * @return string The AI-generated response.
     * @throws Exception If there's an error in the request or if the API doesn't return valid content.
     */
    public function getAIResponse(string $prompt): string
    {
        // Asegúrate de que el cliente y el modelo no sean nulos antes de usarlos.
        // Después de la configuración en el constructor, no deberían ser nulos.
        if (is_null($this->client) || is_null($this->model)) {
            throw new Exception("El cliente de la API o el modelo no están configurados correctamente.");
        }

        $data = [
            'contents' => [
                [
                    'parts' => [['text' => $prompt]],
                ],
            ],
        ];

        try {
            $response = $this->client->post($this->model . ':generateContent', [
                'json' => $data,
            ]);

            $body = json_decode($response->getBody(), true);
            $generatedText = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$generatedText) {
                throw new Exception("La API no devolvió contenido válido o en el formato esperado.");
            }
            return $generatedText;
        } catch (RequestException $e) {
            throw new Exception("Error en la solicitud a la API: " . $e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new Exception("Error al procesar la respuesta de la API: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}