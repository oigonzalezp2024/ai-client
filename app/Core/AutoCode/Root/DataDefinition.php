<?php

namespace App\Core\AutoCode\Root;

class DataDefinition
{
    private string $fieldName;
    private string $dataType;
    private string $columnName;
    private bool $isPrimaryKey;
    private bool $isAutoincrement;
    private bool $isNullable;
    private mixed $defaultValue; // Usamos 'mixed' para permitir null, string, int, bool

    public function __construct(
        string $fieldName,
        string $dataType,
        string $columnName,
        bool $isPrimaryKey,
        bool $isAutoincrement,
        bool $isNullable,
        mixed $defaultValue
    ) {
        $this->fieldName = $fieldName;
        $this->dataType = $dataType;
        $this->columnName = $columnName;
        $this->isPrimaryKey = $isPrimaryKey;
        $this->isAutoincrement = $isAutoincrement;
        $this->isNullable = $isNullable;
        $this->defaultValue = $defaultValue;
    }

    /**
     * Método estático para crear una nueva instancia de DataDefinition.
     * Facilita la creación sin tener que usar 'new DataDefinition(...)' directamente.
     */
    public static function create(
        string $fieldName,
        string $dataType,
        string $columnName,
        bool $isPrimaryKey,
        bool $isAutoincrement,
        bool $isNullable,
        mixed $defaultValue
    ): self {
        return new self(
            $fieldName,
            $dataType,
            $columnName,
            $isPrimaryKey,
            $isAutoincrement,
            $isNullable,
            $defaultValue
        );
    }

    // --- Métodos Getters ---

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function isPrimaryKey(): bool
    {
        return $this->isPrimaryKey;
    }

    public function isAutoincrement(): bool
    {
        return $this->isAutoincrement;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }
}
