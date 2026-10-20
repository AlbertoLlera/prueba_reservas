<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Core\Exceptions\ValidationException;
use App\Core\Request;
use App\Core\Response;
use App\Services\ReservaService;


final class ReservaController
{
    public function __construct(private readonly ReservaService $service) {}

    public function index(Request $request): void
    {
        $filtros = [
            'estado'      => $request->query('estado'),
            'fecha_desde' => $request->query('fecha_desde'),
            'fecha_hasta' => $request->query('fecha_hasta'),
            'busqueda'    => $request->query('busqueda'),
        ];

        $filtros = array_filter($filtros, fn($v) => $v !== null);

        $reservas = $this->service->listar($filtros);

        $data = array_map(fn($r) => $r->toArray(), $reservas);

        Response::json([
            'data'  => $data,
            'total' => count($data),
        ], 200)->send();
    }


    public function show(Request $request, array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $reserva = $this->service->obtener($id);

        Response::json([
            'data' => $reserva->toArray(),
        ], 200)->send();
    }

    public function store(Request $request): void
    {
        $data = $request->json();

        if (empty($data)) {
            throw new ValidationException([
                'body' => 'El cuerpo de la petición debe ser JSON válido.',
            ]);
        }

        $reserva = $this->service->crear($data);

        Response::json([
            'data' => $reserva->toArray(),
        ], 201)->send();
    }

    public function cambiarEstado(Request $request, array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $data = $request->json();
        $nuevoEstado = $data['estado'] ?? null;

        if ($nuevoEstado === null) {
            throw new ValidationException([
                'estado' => 'El campo estado es obligatorio.',
            ]);
        }

        $reserva = $this->service->cambiarEstado($id, (string) $nuevoEstado);

        Response::json([
            'data' => $reserva->toArray(),
        ], 200)->send();
    }
}