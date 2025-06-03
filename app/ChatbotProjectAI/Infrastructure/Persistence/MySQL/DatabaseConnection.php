<?php

namespace App\Chatbot\Infrastructure\Persistence\MySQL;

use PDO;
use PDOException;

class DatabaseConnection
{
    private string $host;
    private string $dbName;
    private string $user;
    private string $password;
    private ?PDO $pdo = null;

    public function __construct(string $host = 'localhost', string $dbName = 'mydatabase', string $user = 'root', string $password = '')
    {
        $this->host = $host;
        $this->dbName = $dbName;
        $this->user = $user;
        $this->password = $password;
    }

    public function connect(): PDO
    {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
            } catch (PDOException $e) {
                // En un entorno de producción, loguear el error y mostrar un mensaje genérico.
                // Aquí lo lanzamos para depuración.
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->pdo;
    }

    public function disconnect(): void
    {
        $this->pdo = null;
    }
}