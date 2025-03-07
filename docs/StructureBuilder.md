# Clase `StructureBuilder`

La clase `StructureBuilder` se encarga de crear la estructura de archivos y directorios de un proyecto a partir de un archivo de texto que define dicha estructura.

#### **Uso**

```php
use App\Core\StructureBuilder;

// Creación de estructura de archivos.
$estructura = new StructureBuilder('../docs/structureFile.txt');
$estructura->createStructure();
echo "Estructura de proyectos creada.";
```

#### **Funcionamiento**
- **Entrada**: Un archivo de texto con la jerarquía de carpetas y archivos.
- **Procesamiento**: Lee el archivo línea por línea, identificando directorios y archivos.
- **Salida**: Crea la estructura definida en el sistema de archivos.

#### **Métodos principales**
- `__construct($structureFile)`: Recibe la ruta del archivo de estructura.
- `createStructure()`: Procesa y crea la estructura.
- `createDirectory($path)`: Crea un directorio si no existe.
- `createFile($file)`: Crea un archivo si no existe.

---

## Conclusión
Esta clase facilita la organización de archivos.

## [volver](InformeDesarrollo.md)