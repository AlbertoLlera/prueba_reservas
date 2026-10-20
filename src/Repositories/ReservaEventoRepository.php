<?php

    declare(strict_types=1);
     namespace App\Repositories;

     use PDO;

     final class ReservaEventoRepository{

        public function __construct(private readonly PDO $pdo) {}

        public function add(int $reservaId, string $tipo, string $descripcion): void {

            $stmt = $this->pdo->prepare(
                'INSERT INTO reserva_evento (reserva_id, tipo, descripcion) VALUES (:reserva_id, :tipo, :descripcion)'
            );
            $stmt->execute([
                'reserva_id' => $reservaId,
                'tipo' => $tipo,
                'descripcion' => $descripcion
            ]);
        }

        public function findByReserva(int $reservaId) : array {

            $stmt = $this->pdo->prepare(
                'SELECT * FROM reserva_evento
                WHERE reserva_id = :reserva_id
                ORDER BY fecha_creacion ASC, id ASC'
            );

            $stmt->execute([
                'reserva_id' => $reservaId
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }

     }
     

