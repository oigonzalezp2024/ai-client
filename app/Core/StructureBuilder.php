<?php
/*
Pasar por favor esta clase de PHP a python y da un ejemplo de uso.
*/

namespace App\Core;

/**
 * Clase para construir una estructura de archivos y directorios a partir de un archivo de texto.
 */
class StructureBuilder
{
    /**
     * @var string Contenido del archivo de estructura.
     */
    private $structure;
    
    /**
     * @var string Directorio raíz de la estructura.
     */
    private $rootDirectory;
    
    /**
     * @var string Directorio padre actual.
     */
    private $parentDirectory;
    
    /**
     * @var string Directorio hijo actual.
     */
    private $childDirectory;
    
    /**
     * @var string Directorio actual en proceso de creación.
     */
    private $currentDirectory;
    
    /**
     * @var string Último directorio procesado.
     */
    private $folderDirectory;

    /**
     * Constructor de la clase.
     *
     * @param string $structureFile Ruta al archivo que contiene la estructura.
     */
    public function __construct(string $structureFile)
    {
        $this->structure = file_get_contents($structureFile);
    }

    /**
     * Crea la estructura de archivos y directorios basada en el archivo de estructura.
     */
    public function createStructure(): void
    {
        $lines = explode("\n", trim($this->structure));

        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, '/') === 0) {
                // Es un directorio raíz
                $this->rootDirectory = $line;
                $this->currentDirectory = $this->rootDirectory;
                $path = "./" . $this->rootDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '├── /') === 0) {
                // Es un directorio dentro del directorio raíz
                $this->parentDirectory = $this->rootDirectory . $this->cleanLine($line);
                $this->currentDirectory = $this->parentDirectory;
                $path = "./" . $this->parentDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '│   ├── /') === 0 || strpos($line, '│   └── /') === 0) {
                // Es un subdirectorio dentro del directorio padre
                $this->childDirectory = $this->parentDirectory . $this->cleanLine($line);
                $this->folderDirectory = $this->childDirectory;
                $this->currentDirectory = $this->childDirectory;
                $path = "./" . $this->childDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '│   │   ├── /') === 0 || strpos($line, '│   │   └── /') === 0 || strpos($line, '│       ├── /') === 0 || strpos($line, '│       └── /') === 0) {
                // Es un subdirectorio de segundo nivel
                $this->childDirectory = $this->folderDirectory . $this->cleanLine($line, true);
                $this->currentDirectory = $this->childDirectory;
                $path = "./" . $this->childDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '├──') === 0 || strpos($line, '└──') === 0) {
                // Es un archivo en el directorio raíz
                $file = "./" . $this->rootDirectory . "/" . trim($this->cleanLine($line));
                $this->createFile($file);
            } elseif (strpos($line, '│   ├──') === 0 || strpos($line, '│   └──') === 0) {
                // Es un archivo dentro del directorio padre
                $file = "./" . $this->parentDirectory . "/" . trim($this->cleanLine($line));
                $this->createFile($file);
            } elseif (strpos($line, '│   │   ├──') === 0 || strpos($line, '│   │   └──') === 0 || strpos($line, '│       ├──') === 0 || strpos($line, '│       └──') === 0) {
                // Es un archivo dentro de un subdirectorio
                $file = "./" . $this->currentDirectory . "/" . trim($this->cleanLine($line, true));
                $this->createFile($file);
            } else {
                // Es un archivo en el directorio actual
                $file = "./" . $this->currentDirectory . "/" . trim($this->cleanLine($line));
                $this->createFile($file);
            }
        }
    }

    /**
     * Limpia una línea eliminando los caracteres de estructura visual.
     *
     * @param string $line Línea a limpiar.
     * @param bool $double Indica si se deben limpiar niveles más profundos.
     * @return string Línea limpia.
     */
    private function cleanLine(string $line, bool $double = false): string
    {
        $replacements = ['├── ', '│   ├── ', '│   └── ', '└── ', '│   '];
        if ($double) {
            $replacements = ['│   ├── ', '│   │   ├── ', '│   │   └── ', '│       ├── ', '│       └── ', '│   '];
        }
        return str_replace($replacements, '', $line);
    }

    /**
     * Crea un directorio si no existe.
     *
     * @param string $path Ruta del directorio a crear.
     */
    private function createDirectory(string $path): void
    {
        if (is_dir($path)) {
            echo "El directorio '$path' ya existe.\n";
        } else {
            mkdir($path, 0777, true);
            echo "Directorio creado: $path\n";
        }
    }

    /**
     * Crea un archivo si no existe.
     *
     * @param string $file Ruta del archivo a crear.
     */
    private function createFile(string $file): void
    {
        if (file_exists($file)) {
            echo "El archivo '$file' ya existe.\n";
        } else {
            echo "Creando archivo: $file\n";
            
            $directory = dirname($file);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            
            if (!is_writable($directory)) {
                die("Error: No tienes permisos de escritura en '$directory'.\n");
            }
            
            $fileHandle = fopen($file, "w");
            if (!$fileHandle) {
                die("Error: No se pudo abrir el archivo '$file' para escritura.\n");
            }
            
            fclose($fileHandle);
            echo "Archivo creado con éxito: $file\n";
        }
    }
}
