
# AI Client

AI Client es un cliente PHP diseñado para interactuar con APIs de Inteligencia Artificial, facilitando la integración de capacidades de IA en tus proyectos de software.  Actualmente, está configurado para interactuar con la API de Gemini de Google, pero puede extenderse para soportar otros modelos.

## Características

*   **Fácil de usar:**  Abstrae la complejidad de las llamadas a la API, permitiendo procesar archivos de texto con prompts y obtener respuestas generadas por IA.
*   **Configurable:** Utiliza variables de entorno para la configuración de la API Key, la URL base de la API y el modelo de IA a utilizar.
*   **Manejo de errores:** Implementa manejo de excepciones para identificar y solucionar problemas durante la comunicación con la API.
*   **Dependencias:** Utiliza bibliotecas robustas como `GuzzleHttp` para realizar peticiones HTTP y `vlucas/phpdotenv` para gestionar las variables de entorno.

## Requisitos

*   PHP >= 8.2
*   Composer

## Instalación

1.  Clona el repositorio:

    ```bash
    git clone https://github.com/oigonzalezp2024/ai-client.git
    cd ai-client
    ```

2.  Instala las dependencias con Composer:

    ```bash
    composer install
    ```

## Configuración

1.  Crea un archivo `.env` en el directorio raíz del proyecto.

2.  Define las siguientes variables de entorno en el archivo `.env`:

    ```
    API_KEY=tu_api_key
    API_BASE_URI=https://generativelanguage.googleapis.com/v1beta/
    AI_MODEL=models/gemini-1.5-pro-latest
    ```

    *   `API_KEY`: Tu clave de API para acceder al servicio de IA.
    *   `API_BASE_URI`:  La URL base de la API.
    *   `AI_MODEL`: El modelo de IA que se va a utilizar (por ejemplo, `models/gemini-1.5-pro-latest`).

    **Importante:**  Nunca subas tu archivo `.env` a un repositorio público.

## Uso

1.  Crea un archivo de texto con el prompt que quieres enviar a la API en el directorio `storage/ai_input/`.  Por ejemplo, `storage/ai_input/prompt.txt`.

2.  Ejecuta el script `app/index.php`:

    ```bash
    php app/index.php
    ```

3.  La respuesta generada por la IA se guardará en el archivo `storage/ai_output/response.txt`.

## Ejemplo

**storage/ai_input/prompt.txt:**

```
Escribe un breve resumen de la novela "1984" de George Orwell.
```

**Ejecución:**

```bash
php app/index.php
```

**storage/ai_output/response.txt:**

```
"1984" de George Orwell es una novela distópica que describe una sociedad totalitaria controlada por el Partido, liderado por el omnipresente Gran Hermano. Winston Smith, el protagonista, trabaja en el Ministerio de la Verdad, donde altera la historia para que coincida con la propaganda del Partido. Frustrado por la opresión, Winston se rebela iniciando una relación prohibida con Julia. Ambos son capturados y sometidos a tortura y lavado de cerebro, obligándolos a traicionar sus creencias y a amara al Gran Hermano. La novela explora temas como la vigilancia masiva, el control del pensamiento, la manipulación de la verdad y la pérdida de la individualidad en un régimen totalitario.
```

## Estructura del Proyecto

```
ai-client/
├── app/
│   ├── Core/
│   │   └── AIFileProcessor.php  # Clase principal para procesar archivos con la API de IA
│   └── index.php                # Ejemplo de uso de la clase AIFileProcessor
├── storage/
│   ├── ai_input/                # Directorio para los archivos de entrada (prompts)
│   └── ai_output/               # Directorio para los archivos de salida (respuestas de la IA)
├── vendor/                      # Dependencias de Composer
├── composer.json                # Archivo de configuración de Composer
└── .env                         # Archivo para las variables de entorno (¡no lo subas a un repositorio público!)
```

## Contribución

Las contribuciones son bienvenidas. Por favor, abre un issue para discutir los cambios propuestos antes de enviar un pull request.

## Licencia

Este proyecto está licenciado bajo la licencia Apache-2.0.  Ver el archivo `LICENSE` para más detalles.

