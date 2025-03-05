<?php

namespace App\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dotenv\Dotenv;
use Exception;

class AIFileProcessor
{
    private Client $client;
    private string $apiKey;
    private string $baseUri;
    private string $model;

    public function __construct()
    {
        // Cargar variables de entorno
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $this->apiKey = getenv('API_KEY') ?: $_ENV['API_KEY'];
        $this->baseUri = getenv('API_BASE_URI') ?: $_ENV['API_BASE_URI'];
        $this->model = getenv('AI_MODEL') ?: $_ENV['AI_MODEL'];

        // Configurar cliente HTTP
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
     * Lee un archivo, lo usa como prompt, obtiene la respuesta y la guarda en otro archivo.
     */
    public function processFile(string $inputPath, string $outputPath): bool
    {
        if (!file_exists($inputPath)) {
            throw new Exception("El archivo de entrada no existe: $inputPath");
        }

        // Leer el contenido del archivo
        $prompt = file_get_contents($inputPath);
        if ($prompt === false) {
            throw new Exception("No se pudo leer el archivo: $inputPath");
        }

        // Enviar solicitud a la API
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
                throw new Exception("La API no devolvió contenido válido.");
            }

            // Guardar el resultado en el archivo de salida
            if (file_put_contents($outputPath, $generatedText) === false) {
                throw new Exception("Error al escribir el archivo: $outputPath");
            }

            return true;
        } catch (RequestException $e) {
            throw new Exception("Error en la solicitud a la API: " . $e->getMessage());
        }
    }
}
