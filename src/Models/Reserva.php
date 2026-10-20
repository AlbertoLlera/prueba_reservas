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
        public readOnly string $fechaSalida,
        public readOnly string $estado,
        public readOnly float $importe,
        public readOnly ?string $notas,
        public readOnly ?string $fechaCreacion = null,

        ) {}

        public static function fromArray(array $data): self {
            $row = $data;

            return new self(
                id: isset($row['id']) ? (int) $row['id'] : null,
                nombreHuesped: $row['nombre_huesped'] ?? '',
                emailHuesped: $row['email_huesped'] ?? '',
                nombreAlojamiento: $row['nombre_alojamiento'] ?? '',
                fechaEntrada: $row['fecha_entrada'] ?? '',
                fechaSalida: $row['fecha_salida'] ?? '',
                estado: $row['estado'] ?? '',
                importe: isset($row['importe']) ? (float) $row['importe'] : 0.0,
                notas: $row['notas'] ?? null,
                fechaCreacion: $row['fecha_creacion'] ?? null
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
                'precioTotal' => $this->importe,
                'notas' => $this->notas,
                'fechaCreacion' => $this->fechaCreacion
            ];
        }

    }