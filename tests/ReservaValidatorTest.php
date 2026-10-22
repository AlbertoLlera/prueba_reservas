<?php

declare(strict_types=1);

namespace Tests;

use App\Validators\ReservaValidator;
use PHPUnit\Framework\TestCase;

final class ReservaValidatorTest extends TestCase
{
    private ReservaValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ReservaValidator();
    }

    public function testTransicionValidaDePendienteAConfirmada(): void
    {
        $error = $this->validator->validateTransicion('pendiente', 'confirmada');
        $this->assertNull($error, 'Pendiente -> Confirmada debería ser válida.');
    }

    public function testTransicionInvalidaDeConfirmadaAPendiente(): void
    {
        $error = $this->validator->validateTransicion('confirmada', 'pendiente');
        $this->assertNotNull($error, 'Confirmada -> Pendiente NO debería ser válida.');
        $this->assertStringContainsString('Transición de estado no permitida', $error);
    }

    public function testTransicionInvalidaDesdeCancelada(): void
    {
        $error = $this->validator->validateTransicion('cancelada', 'confirmada');
        $this->assertNotNull($error, 'Cancelada -> Confirmada NO debería ser válida.');
    }

    public function testValidacionDeDatosCorrectos(): void
    {
        $data = [
            'nombre_huesped'     => 'Ana García',
            'email_huesped'      => 'ana@example.com',
            'nombre_alojamiento' => 'Hotel Test',
            'fecha_entrada'      => '2026-05-01',
            'fecha_salida'       => '2026-05-05',
            'importe'            => 250.00,
        ];

        $errors = $this->validator->validate($data);
        $this->assertEmpty($errors, 'Los datos válidos no deberían devolver errores.');
    }

    public function testValidacionDeEmailInvalido(): void
    {
        $data = [
            'nombre_huesped'     => 'Ana García',
            'email_huesped'      => 'esto-no-es-un-email',
            'nombre_alojamiento' => 'Hotel Test',
            'fecha_entrada'      => '2026-05-01',
            'fecha_salida'       => '2026-05-05',
            'importe'            => 250.00,
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('email_huesped', $errors);
    }

    public function testValidacionDeFechasIncoherentes(): void
    {
        $data = [
            'nombre_huesped'     => 'Ana García',
            'email_huesped'      => 'ana@example.com',
            'nombre_alojamiento' => 'Hotel Test',
            'fecha_entrada'      => '2026-05-10',
            'fecha_salida'       => '2026-05-05', // Salida ANTES que entrada
            'importe'            => 250.00,
        ];

        $errors = $this->validator->validate($data);
        $this->assertArrayHasKey('fecha_salida', $errors);
    }
}