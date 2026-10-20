<?php

    declare(strict_types=1);

    namespace App\Validators;

    final class ReservaValidator {

        
        public function validate(array $data): array {
        
            $errors = [];

            $requiredFields = [
                'nombre_huesped',
                'email_huesped',
                'nombre_alojamiento',
                'fecha_entrada',
                'fecha_salida',
                'importe',
            ];

            // Validar que los campos ablogatorios estén presentes y no enstén vacios
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $errors[$field] = "El campo {$field} es obligatorio.";
                    continue;
                }

                if (trim((string) $data[$field]) === '') {
                    $errors[$field] = "El campo {$field} es obligatorio.";
                }
            }

            // Validar que el email sea correcto y válido
            if (isset($data['email_huesped']) && $data['email_huesped'] !== ''){

                if (!filter_var($data['email_huesped'], FILTER_VALIDATE_EMAIL)){
                    $errors['email_huesped'] = "El campo email_huesped debe ser un correo electrónico válido.";
                } 
            }
            
            //Algunas validaciones para las fechas de entrada y salida
            $entrada = $data['fecha_entrada'] ?? null;
            $salida = $data['fecha_salida'] ?? null;

            if ($entrada && !$this->isValidDate($entrada)) {
                $errors['fecha_entrada'] = "El campo fecha_entrada debe ser una fecha válida en formato YYYY-MM-DD.";
            }

            if ($salida && !$this->isValidDate($salida)) {
                $errors['fecha_salida'] = "El campo fecha_salida debe ser una fecha válida en formato YYYY-MM-DD.";
            }

            if (
                $entrada && $salida 
                && $this->isValidDate($entrada) && $this->isValidDate($salida)
                && $salida <= $entrada
            ) {
                $errors['fecha_salida'] = "El campo fecha de salida debe ser posterior a fecha de entrada.";
            }

            // Validación de importe númerico y que además sea positivo
            if (isset($data['importe']) && $data['importe'] !== '') {
                if (!is_numeric($data['importe'])) {
                    $errors['importe'] = 'El importe debe ser un número.';
                } elseif ((float) $data['importe'] < 0) {
                    $errors['importe'] = 'El importe no puede ser negativo.';
                }
            }   

            // Comprobación de que el estado sea válido
            if (isset($data['estado']) && $data['estado'] !== '') {
                $estadosValidos = ['pendiente', 'confirmada', 'cancelada'];
                if (!in_array($data['estado'], $estadosValidos, true)) {
                    $errors['estado'] = 'Estado no válido.';
                }
            }

            return $errors;
        }

        public function validateTransicion(string $estadoActual, string $nuevoEstado) : ?string {

            $transicionesValidas = [
                'pendiente' => ['confirmada', 'cancelada'],
                'confirmada' => ['cancelada'],
                'cancelada' => [],
            ];

            if (!isset($transicionesValidas[$estadoActual])) {
                return "Estado actual desconocido: {$estadoActual}";
            }

            if (!in_array($nuevoEstado, $transicionesValidas[$estadoActual], true)) {
                return "Transición de estado no permitida: {$estadoActual} a {$nuevoEstado}";
            }

            return null;
        }

        private function isValidDate(string $date): bool{
            $d = \DateTime::createFromFormat('Y-m-d', $date);
            return $d !== false && $d->format('Y-m-d') === $date;
        }

    }