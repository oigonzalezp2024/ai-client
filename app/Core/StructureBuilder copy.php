<?php

namespace App\Core;

class StructureBuilder
{
    private $structure;
    private $rootDirectory;
    private $parentDirectory;
    private $childDirectory;
    private $currentDirectory;
    private $folderDirectory;

    public function __construct(string $structureFile)
    {
        $this->structure = file_get_contents($structureFile);
    }

    public function createStructure(): void
    {
        $lines = explode("\n", trim($this->structure));

        foreach ($lines as $line) {
            $line = trim($line);

            if (strpos($line, '/') === 0) {
                $this->rootDirectory = $line;
                $this->currentDirectory = $this->rootDirectory;
                $path = ".//" . $this->rootDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '│── /') === 0) {
                $this->parentDirectory = $this->rootDirectory . $this->cleanLine($line);
                $this->currentDirectory = $this->parentDirectory;
                $path = ".//" . $this->parentDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '│   ├── /') === 0 || strpos($line, '│   └── /') === 0) {
                $this->childDirectory = $this->parentDirectory . $this->cleanLine($line);
                $this->folderDirectory = $this->childDirectory;
                $this->currentDirectory = $this->childDirectory;
                $path = ".//" . $this->childDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '│   │   ├── /') === 0 || strpos($line, '│   │   └── /') === 0 || strpos($line, '│       ├── /') === 0 || strpos($line, '│       └── /') === 0) {
                $this->childDirectory = $this->folderDirectory . $this->cleanLine($line, true);
                $this->currentDirectory = $this->childDirectory;
                $path = ".//" . $this->childDirectory;
                $this->createDirectory($path);
            } elseif (strpos($line, '│──') === 0 || strpos($line, '└──') === 0) {
                $file = "./" . $this->rootDirectory . "/" . trim($this->cleanLine($line));
                $this->createFile($file);
            } elseif (strpos($line, '│   ├──') === 0 || strpos($line, '│   └──') === 0) {
                $file = "./" . $this->parentDirectory . "/" . trim($this->cleanLine($line));
                $this->createFile($file);
            } elseif (strpos($line, '│   │   ├──') === 0 || strpos($line, '│   │   └──') === 0 ||strpos($line, '│       ├──') === 0 || strpos($line, '│       └──') === 0 ) {
                $file = "./" . $this->currentDirectory . "/" . trim($this->cleanLine($line, true));
                $this->createFile($file);
            } else {
                $file = "./" . $this->currentDirectory . "/" . trim($this->cleanLine($line));
                $this->createFile($file);
            }
        }
    }

    private function cleanLine(string $line, bool $double = false): string
    {
        $replacements = ['│── ', '│   ├── ', '│   └── ', '└── ', '│   '];
        if ($double) {
            $replacements = ['│   │── ', '│   │   ├── ', '│   │   └── ', '│       ├── ', '│       └── ', '│   '];
        }
        return str_replace($replacements, '', $line);
    }

    private function createDirectory(string $path): void
    {
        if (is_dir($path)) {
            echo "The directory '$path' already exists.\n";
        } else {
            mkdir($path, 0777, true);
            echo "Directory created: $path\n";
        }
    }

    private function createFile(string $file): void
    {
        if (file_exists($file)) {
            echo "The file '$file' already exists.\n";
        } else {
            echo "Creating file: $file\n";
            // Check if the directory exists
            $directory = dirname($file);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            // Check write permissions
            if (!is_writable($directory)) {
                die("Error: You do not have write permissions in '$directory'.\n");
            }
            
            // Try to open the file
            $fileHandle = fopen($file, "w");
            if (!$fileHandle) {
                die("Error: Could not open the file '$file' for writing.\n");
            }
            
            fclose($fileHandle);

            echo "File created successfully: $file\n";
        }
    }
}
