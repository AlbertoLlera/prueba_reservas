<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use App\Core\HttpException;

/**
 * Excepción para conflictos con el estado actual del recurso (HTTP 409).
 *
 * Ejemplos de uso:
 *  - Intentar confirmar una reserva que ya está cancelada.
 *  - Intentar crear una reserva duplicada.
 */
final class ConflictException extends HttpException
{
    public function __construct(string $message = 'Conflicto con el estado actual del recurso')
    {
        parent::__construct($message, 409);
    }
}