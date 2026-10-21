<?php
    declare(strict_types=1);

    namespace App\Controllers\Web;

    use App\Core\Request;
    use App\Core\Response;
    use App\Repositories\ReservaEventoRepository;
    use App\Services\ReservaService;
    use Twig\Environment;

    final class ReservaWebController {
        public function __construct(
            private readonly ReservaService $service,
            private readonly ReservaEventoRepository $eventoRepo,
            private readonly Environment $twig
        ){}

        public function index(Request $request): void {
            $filtros = [
                'estado'      => $request->query('estado'),
                'fecha_desde' => $request->query('fecha_desde'),
                'fecha_hasta' => $request->query('fecha_hasta'),
                'busqueda'    => $request->query('busqueda'),
            ];
            $filtros = array_filter($filtros, fn($v) => $v !== null);

            $reservas = $this->service->listar($filtros);

            $html = $this->twig->render('reservas/listado.twig', [
                'reservas' => $reservas,
                'filtros'  => $filtros,
            ]);

            (new Response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']))->send();
        }

        public function show(Request $request, array $params): void {
            $id = (int) ($params['id'] ?? 0);
            $reserva = $this->service->obtener($id);
            $eventos = $this->eventoRepo->findByReserva($id);

            $html = $this->twig->render('reservas/detalle.twig', [
                'reserva' => $reserva,
                'eventos' => $eventos,
            ]);

            (new Response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']))->send();
        }

        public function crear(Request $request): void {
            $html = $this->twig->render('reservas/crear.twig', []);
            (new Response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']))->send();
        }

    }
    