<?php

    declare(strict_types=1);

    namespace App\Models;

    final class Reserva {

        public function __construct(

        public readOnly ?int $id,
        public readOnly string $nombreHuesped,
        public readOnly string $emailHuesped,
        public readonly string $nombreAlojamiento,
        public readOnly string $fechaEntrada,
        public readOnly string $estado,
        public readOnly float $precioTotal,
        public readOnly ?string $notas,
        public readOnly ?string $fechaCreacion = null,

        ) {}

        public function fromArray(array $data): self {
            $row = $data;

            return new self(
                id: isset($row['id']) ? (int) $row['id'] : null,
                nombreHuesped: $row['nombreHuesped'] ?? '',
                emailHuesped: $row['emailHuesped'] ?? '',
                nombreAlojamiento: $row['nombreAlojamiento'] ?? '',
                fechaEntrada: $row['fechaEntrada'] ?? '',
                estado: $row['estado'] ?? '',
                precioTotal: isset($row['precioTotal']) ? (float) $row['precioTotal'] : 0.0,
                notas: $row['notas'] ?? null,
                fechaCreacion: $row['fechaCreacion'] ?? null
            );
        }

        public function toArray(): array {
            return [
                'id' => $this->id,
                'nombreHuesped' => $this->nombreHuesped,
                'emailHuesped' => $this->emailHuesped,
                'nombreAlojamiento' => $this->nombreAlojamiento,
                'fechaEntrada' => $this->fechaEntrada,
                'estado' => $this->estado,
                'precioTotal' => $this->precioTotal,
                'notas' => $this->notas,
                'fechaCreacion' => $this->fechaCreacion
            ];
        }

    }