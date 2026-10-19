<?php
   declare(strict_types=1);
   
   namespace App\Core;

   use App\Core\Exceptions\NotFoundException;

   final class Router {

   
       private array $routes = [];

       public function add(string $method, string $pattern, callable|array $handler): void {
           $this->routes[] = [
               'method' => strtoupper($method),
               'pattern' => $pattern,
               'handler' => $handler,
           ];
       }

       public function dispatch(Request $request) {
        

        $method = $request->getMethod();
        $path = $this->normalizePath($request->getPath());

        foreach ($this->routes as $route) {
               if ($route['method'] !== $method) {
                   continue;
               }

               $params = $this->match($route['pattern'], $path);

               if ($params === null) {
                    continue;
               }

               $this->invoke($route['handler'], $request, $params);
               return;

           }
           throw new NotFoundException("Ruta no encontrada: {$method} {$path}");
       }

       private function match(string $pattern, string $path): ?array {

            $normalized = $this->normalizePath($pattern);

            $regex = preg_replace(
                '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
                '(?P<$1>[^/]+)',
                $normalized
            );

            $regex = '#^' . $regex . '$#';

                    if (!preg_match($regex, $path, $matches)) {
            return null;
            }

            // Solo devolver los grupos con nombre (los parámetros)
            return array_filter(
                $matches,
                fn($key) => is_string($key),
                ARRAY_FILTER_USE_KEY
            );
       }

       private function invoke(callable|array $handler, Request $request, array $params): void
        {
            if (is_array($handler) && is_string($handler[0] ?? null)) {
                [$class, $method] = $handler;
                $controller = new $class();
                $controller->$method($request, $params);
                return;
            }

            if (is_callable($handler)) {
                $handler($request, $params);
            }
        }

        private function normalizePath(string $path): string
        {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
            $baseDir = rtrim(str_replace('/index.php', '', $scriptName), '/');

            if ($baseDir !== '' && str_starts_with($path, $baseDir)) {
                $path = substr($path, strlen($baseDir));
            }

            if (!str_starts_with($path, '/')) {
                $path = '/' . $path;
            }

            if ($path !== '/' && str_ends_with($path, '/')) {
                $path = rtrim($path, '/');
            }

            return $path;
        }
   }