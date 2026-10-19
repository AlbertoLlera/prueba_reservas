<?php

    declare(strict_types=1);

    namespace App\Core;

    final class Request {
        private string $method;
        private string $path;
        private array $query;
        private array $headers;
        private string $rawBody;

        public function __construct (

            string $method,
            string $path,
            array $query = [],
            array $headers = [],
            string $rawBody = ''
        ){
            $this->method = strtoupper($method);
            $this->path = $path;
            $this->query = $query;
            $this->headers = array_change_key_case($headers, CASE_LOWER);
            $this->rawBody = $rawBody;
        }

        public static function fromGlobals(): self {
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
            $query = $_GET;
            $headers = function_exists('getallheaders') ? getallheaders() : [];
            $rawBody = file_get_contents('php://input') ?: '';

            return new self($method, $path, $query, $headers, $rawBody);
        }

        public function getMethod(): string {
            return $this->method;
        }


        public function getPath(): string {
            return $this->path;
        }

        public function query (string $key, ?string $default = null): ?string {
            return $this->query[$key] ?? $default;
        }

        public function allQuery(): array {
            return $this->query;
        }

        public function header(string $name): ?string {
            
            return $this->headers[strtolower($name)] ?? null;
        }

        public function json(): array {

            if ($this->rawBody === '') {
                return [];
            }

            $decoded = json_decode($this->rawBody, true);
            return is_array($decoded) ? $decoded : [];
        }
    }