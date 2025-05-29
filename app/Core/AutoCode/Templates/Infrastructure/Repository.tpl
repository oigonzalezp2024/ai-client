<?php

namespace {{NAMESPACE}};

use PDO;
use {{ENTITY_NAMESPACE}}\{{ENTITY_NAME}};
use {{REPOSITORY_INTERFACE_NAMESPACE}}\{{REPOSITORY_INTERFACE_NAME}};

class {{ENTITY_NAME}}Repository implements {{REPOSITORY_INTERFACE_NAME}}
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findById({{ID_PHP_TYPE}} $id): ?{{ENTITY_NAME}}
    {
        $stmt = $this->connection->prepare("SELECT * FROM {{TABLE_NAME}} WHERE {{ID_FIELD}} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return {{ENTITY_NAME}}::fromArray($data);
    }

    public function save({{ENTITY_NAME}} ${{REPO_VAR_NAME}}): {{ENTITY_NAME}}
    {
{{SAVE_METHOD_BODY}}
    }

    public function delete({{ENTITY_NAME}} ${{REPO_VAR_NAME}}): void
    {
{{DELETE_METHOD_BODY}}
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM {{TABLE_NAME}}");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $entities = [];
        foreach ($results as $data) {
            $entities[] = {{ENTITY_NAME}}::fromArray($data);
        }
        return $entities;
    }
}