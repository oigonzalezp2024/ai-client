<?php

namespace App\Core;

class DirectoryStructure {
    private $basePath;
    private string $currentPath = '';

    public function __construct($path) {
        $this->basePath = $path;
    }

    private function customSort(string $a, string $b): int {
        $pathA = $this->basePath . '/' . $this->currentPath . '/' . $a;
        $pathB = $this->basePath . '/' . $this->currentPath . '/' . $b;
        $aIsDir = is_dir($pathA);
        $bIsDir = is_dir($pathB);
        $aIsDot = str_starts_with($a, '.');
        $bIsDot = str_starts_with($b, '.');
        $aIsUpperFile = !$aIsDir && !$aIsDot && $a !== 'README.md' && ctype_upper($a);
        $bIsUpperFile = !$bIsDir && !$bIsDot && $b !== 'README.md' && ctype_upper($b);
        $aIsReadme = $a === 'README.md';
        $bIsReadme = $b === 'README.md';

        // Prioritize directories
        if ($aIsDir && !$bIsDir) {
            return -1;
        } elseif (!$aIsDir && $bIsDir) {
            return 1;
        }

        // Then prioritize dot files/directories
        if ($aIsDot && !$bIsDot) {
            return -1;
        } elseif (!$aIsDot && $bIsDot) {
            return 1;
        }

        // Then prioritize regular files (not starting with dot, not uppercase, and not README.md)
        if (!$aIsDir && !$aIsUpperFile && !$aIsDot && !$aIsReadme && !$bIsDir && ($bIsUpperFile || $bIsReadme)) {
            return -1;
        } elseif ((!$bIsDir && !$bIsUpperFile && !$bIsDot && !$bIsReadme) && (!$aIsDir && ($aIsUpperFile || $aIsReadme))) {
            return 1;
        }

        // Then uppercase files (not README.md)
        if ($aIsUpperFile && !$bIsUpperFile && !$bIsReadme) {
            return -1;
        } elseif (!$aIsUpperFile && $bIsUpperFile && !$aIsReadme) {
            return 1;
        } elseif ($aIsUpperFile && $bIsUpperFile && !$aIsReadme && $bIsReadme) {
            return -1;
        } elseif ($aIsUpperFile && $bIsUpperFile && $aIsReadme && !$bIsReadme) {
            return 1;
        }

        // Finally, README.md
        if ($aIsReadme && !$bIsReadme) {
            return 1;
        } elseif (!$aIsReadme && $bIsReadme) {
            return -1;
        }

        // If none of the above, sort alphabetically
        return strnatcmp($a, $b);
    }

    public function readStructure($path = null, $indentation = '') {
        if ($path === null) {
            $path = $this->basePath;
        }
    
        $result = '';
        $elements = @scandir($path);
    
        if ($elements === false) {
            return "Error: Could not read directory " . $path;
        }
    
        $elements = array_diff($elements, ['.', '..', '.git']); // Keep 'vendor' in the list initially
        $this->currentPath = ltrim(str_replace($this->basePath, '', $path), '/');
        usort($elements, [$this, 'customSort']);
        $totalElements = count($elements);
        $i = 0;
    
        foreach ($elements as $element) {
            $i++;
            $isLastElement = ($i === $totalElements);
            $fullPath = $path . '/' . $element;
            $prefix = $indentation;
            $displayName = $element;
    
            if (is_dir($fullPath)) {
                $displayName = '/' . $element;
            }
    
            if ($indentation !== '') {
                $prefix .= ($isLastElement ? '└── ' : '├── ');
            } else {
                $prefix .= '├── ';
            }
    
            $result .= $prefix . $displayName . "\n";
    
            // Skip reading the contents of the 'vendor' directory
            if ($element === 'vendor' && is_dir($fullPath)) {
                continue; // Do not make a recursive call for 'vendor'
            }
    
            if (is_dir($fullPath)) {
                $newIndentation = $indentation;
                if ($indentation !== '') {
                    $newIndentation .= ($isLastElement ? '    ' : '│   ');
                } else {
                    $newIndentation .= '│   ';
                }
                $result .= $this->readStructure($fullPath, $newIndentation);
            }
        }
    
        return $result;
    }

    public function displayStructure() {
        //echo basename($this->basePath) . "/\n"; // Print the name of the root directory
        return basename($this->basePath)."/\n".$this->readStructure($this->basePath, '');
    }
}
