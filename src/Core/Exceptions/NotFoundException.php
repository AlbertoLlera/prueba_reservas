<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use App\Core\HttpException;


final class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Recurso no encontrado')
    {
        parent::__construct($message, 404);
    }
}