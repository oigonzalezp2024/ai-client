# Clase `ImageUploader`

El módulo `ImageUploader` gestiona la carga de imágenes de forma segura, validada y desacoplada. Sigue los principios SOLID, está diseñado con arquitectura basada en capas (DDD: Domain, Application, Infrastructure) y es compatible con Laravel y con proyectos en PHP puro.

---

## 📦 Estructura del Módulo

```
Modules/
└── ImageUploader/
    ├── Application/
    │   └── Services/
    │       └── UploadImageService.php
    ├── Domain/
    │   ├── Contracts/
    │   │   └── UploaderInterface.php
    │   ├── Exceptions/
    │   │   └── UploadFailedException.php
    │   └── Validators/
    │       └── ImageUploadBusinessValidator.php
    ├── Infrastructure/
    │   ├── Validators/
    │   │   └── TechnicalFileValidator.php
    │   └── LocalFileUploader.php
```

---

## ⚙️ Funcionamiento

### `UploadImageService`

Servicio principal que orquesta todo el proceso de carga:

```php
public function handle(array $file): string
```

Pasos:

1. ✅ **Validación técnica**: Asegura que el archivo fue subido correctamente y tiene extensión, tipo y tamaño válidos.
2. 📊 **Validación de negocio**: Verifica que el archivo cumpla con reglas del dominio (por ejemplo, límites de cantidad por entidad).
3. 📤 **Subida de archivo**: Se delega al `UploaderInterface` para mover el archivo.
4. ❌ **Manejo de errores**: Lanza `UploadFailedException` si alguna etapa falla.

---

## 🧩 Componentes

| Componente | Descripción |
|-----------|-------------|
| **`TechnicalFileValidator`** | Revisa que el archivo sea técnicamente válido (MIME, tamaño, extensión). |
| **`ImageUploadBusinessValidator`** | Aplica reglas del dominio (por ejemplo, restricciones por entidad o lógica personalizada). |
| **`UploaderInterface`** | Contrato que abstrae el mecanismo de carga de archivos. |
| **`LocalFileUploader`** | Implementación de `UploaderInterface`. Mueve y elimina archivos físicamente. |
| **`UploadFailedException`** | Excepción lanzada si alguna validación o el guardado fallan. |

---

## 🧪 Pruebas Unitarias

Archivo: `UploadImageServiceTest.php`

Incluye:
- ✅ Test de carga exitosa con mocks.
- ❌ Test de errores en validaciones técnicas o de negocio.

Ejecutar con PHPUnit:

```bash
vendor/bin/phpunit
```

---

## 💻 Ejemplo de uso (index.php)

```php
use Modules\ImageUploader\Application\Services\UploadImageService;
use Modules\ImageUploader\Infrastructure\Validators\TechnicalFileValidator;
use Modules\ImageUploader\Domain\Validators\ImageUploadBusinessValidator;
use Modules\ImageUploader\Infrastructure\LocalFileUploader;

$uploadService = new UploadImageService(
    new TechnicalFileValidator(),
    new ImageUploadBusinessValidator(),
    new LocalFileUploader(__DIR__ . '/../storage/images_output')
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $path = $uploadService->handle($_FILES['image']);
        echo "Imagen subida a: $path";
    } catch (UploadFailedException $e) {
        echo "Error: " . $e->getMessage();
    }
}
```

---

## 🧾 Formulario HTML simple

```html
<form action="index.php" method="POST" enctype="multipart/form-data">
    <label for="image">Selecciona una imagen:</label>
    <input type="file" name="image" id="image">
    <button type="submit">Subir Imagen</button>
</form>
```

---

## 🚨 Manejo de errores

Todos los errores técnicos o de negocio se encapsulan en:

```php
UploadFailedException
```

Esto permite un control centralizado para log, respuestas amigables o fallback.

---

## ✅ Conclusión

El módulo `ImageUploader` permite subir imágenes de manera validada, segura, escalable y desacoplada del framework. Su diseño modular, extensible y probado con PHPUnit lo hace ideal para proyectos mantenibles en Laravel o PHP puro.

## [volver](InformeDesarrollo.md)
