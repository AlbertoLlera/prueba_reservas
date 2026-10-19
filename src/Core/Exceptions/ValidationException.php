<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use App\Core\HttpException;

final class ValidationException extends HttpException
{
    public function __construct(array $errors, string $message = 'Datos inválidos')
    {
        parent::__construct($message, 400, $errors);
    }
}