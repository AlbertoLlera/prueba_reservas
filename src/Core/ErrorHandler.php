<?php

    declare(strict_types=1);
    
    namespace App\Core;

    use App\Core\Exceptions\NotFoundException;
    use Throwable;

    final Class ErrorHandler {

        private bool $debug;

        public function __construct(bool $debug = false) {
            $this->debug = $debug;
        }

        public function register(): void {
            set_exception_handler(function (Throwable $e): void {
                $this->handle($e);
            });

            // Los warnings/notices también los reconvertimos en excepciones
            // (para que no se silencien)
            set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
                if (!(error_reporting() & $errno)) {
                    return false; // @suppress
                }
                throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
            }); 
        }


        public function handle(Throwable $e): void
        {
            if ($e instanceof ValidationException) {
                Response::json([
                    'error' => $e->getMessage(),
                    'details' => $e->getDetails(),
                ], $e->getStatusCode())->send();
                return;
            }

            if ($e instanceof HttpException) {
                Response::json([
                    'error' => $e->getMessage(),
                ], $e->getStatusCode())->send();
                return;
            }

            // Cualquier otra excepción → 500
            $this->log($e);

            $body = ['error' => 'Error interno del servidor'];
            if ($this->debug) {
                $body['debug'] = [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => $e->getTraceAsString(),
                ];
            }

            Response::json($body, 500)->send();
        }

        private function log(Throwable $e): void
        {
            error_log(sprintf(
                "[%s] %s: %s in %s:%d\n%s",
                date('Y-m-d H:i:s'),
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            ));
        
        }
    }