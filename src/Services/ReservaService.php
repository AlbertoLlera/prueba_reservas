<?php

    declare(strict_types=1);

    namespace App\Services;

    use App\Core\Exceptions\ConflictException;
    use App\Core\Exceptions\NotFoundException;
    use App\Core\Exceptions\ValidationException;
    use App\Models\Reserva;
    use App\Repositories\ReservaRepository;
    use App\Repositories\ReservaEventoRepository;
    use App\Validators\ReservaValidator;
    
    final class ReservaService {

        public function __construct(
            private readonly ReservaRepository $reservas,
            private readonly ReservaEventoRepository $eventos,
            private readonly ReservaValidator $validator,
        ){}

        public function listar(array $filtros = []):array {
    
            return $this->reservas->findAll($filtros);
            
        }


        public function obtener(int $id): Reserva
    {
        $reserva = $this->reservas->findById($id);
        if ($reserva === null) {
            throw new NotFoundException("Reserva con id {$id} no encontrada.");
        }
        return $reserva;
    }


    public function crear(array $data): Reserva
    {
        $errors = $this->validator->validate($data);
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $reserva = new Reserva(
            id: null,
            nombreHuesped: (string) $data['nombre_huesped'],
            emailHuesped: (string) $data['email_huesped'],
            nombreAlojamiento: (string) $data['nombre_alojamiento'],
            fechaEntrada: (string) $data['fecha_entrada'],
            fechaSalida: (string) $data['fecha_salida'],
            estado: 'pendiente',
            importe: (float) $data['importe'],
            notas: isset($data['notas']) ? (string) $data['notas'] : null,
        );

        $id = $this->reservas->create($reserva);

        $this->eventos->add(
            $id,
            'creada',
            'Reserva creada desde la API.'
        );

        return $this->obtener($id);
    }

    /**
     * Cambia el estado de una reserva.
     * Valida la transición y registra evento.
     */
    public function cambiarEstado(int $id, string $nuevoEstado): Reserva
    {
        $reserva = $this->obtener($id);

        $error = $this->validator->validateTransicion($reserva->estado, $nuevoEstado);
        if ($error !== null) {
            throw new ConflictException($error);
        }

        $this->reservas->updateEstado($id, $nuevoEstado);

        $this->eventos->add(
            $id,
            $nuevoEstado,
            "Estado cambiado de '{$reserva->estado}' a '{$nuevoEstado}'."
        );

        return $this->obtener($id);
    }



    }