<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Reserva;
use PDO;

final class ReservaRepository
{
    public function __construct(private readonly PDO $pdo) {}

    
    public function findAll(array $filtros = []): array
    {
        $sql = "SELECT * FROM reserva WHERE 1=1";
        $params = [];

        if (!empty($filtros['estado'])) {
            $sql .= " AND estado = :estado";
            $params['estado'] = $filtros['estado'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $sql .= " AND fecha_entrada >= :fecha_desde";
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $sql .= " AND fecha_entrada <= :fecha_hasta";
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (!empty($filtros['busqueda'])) {
            $sql .= " AND nombre_huesped LIKE :busqueda";
            $params['busqueda'] = '%' . $filtros['busqueda'] . '%';
        }

        $sql .= " ORDER BY fecha_entrada DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll();
        return array_map(fn($row) => Reserva::fromArray($row), $rows);
    }


    public function findById(int $id): ?Reserva
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reserva WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();
        return $row === false ? null : Reserva::fromArray($row);
    }


    public function create(Reserva $reserva): int
    {
        $sql = "INSERT INTO reserva
                    (nombre_huesped, email_huesped, nombre_alojamiento,
                     fecha_entrada, fecha_salida, estado, importe, notas)
                VALUES
                    (:nombre_huesped, :email_huesped, :nombre_alojamiento,
                     :fecha_entrada, :fecha_salida, :estado, :importe, :notas)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'nombre_huesped'     => $reserva->nombreHuesped,
            'email_huesped'      => $reserva->emailHuesped,
            'nombre_alojamiento' => $reserva->nombreAlojamiento,
            'fecha_entrada'      => $reserva->fechaEntrada,
            'fecha_salida'       => $reserva->fechaSalida,
            'estado'             => $reserva->estado,
            'importe'            => $reserva->importe,
            'notas'              => $reserva->notas,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza el estado de una reserva. Devuelve true si afectó a alguna fila.
     */
    public function updateEstado(int $id, string $estado): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE reserva SET estado = :estado WHERE id = :id"
        );
        $stmt->execute(['estado' => $estado, 'id' => $id]);

        return $stmt->rowCount() > 0;
    }
}