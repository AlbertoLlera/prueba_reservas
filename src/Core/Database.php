<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database{
    private PDO $pdo;


    public function __construct(
        string $host,
        int $port,
        string $dbname,
        string $user,
        string $pass
    ){
        $dns = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try{
            $this->pdo = new PDO($dns, $user, $pass, $options);
        } catch (PDOException $e){
            throw new RuntimeException("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }


    public function getConnection(): PDO{
        return $this->pdo;
    }

}