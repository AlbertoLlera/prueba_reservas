<?php

    declare(strict_types=1);
    namespace App\Core;

    final class Response{

        private int $statusCode;
        private array $headers;
        private string $body;
        
        public function __construct(string $body = '', $statusCode = 200, array $headers = []){

            $this->statusCode = $statusCode;
            $this->headers = array_change_key_case($headers, CASE_LOWER);
            $this->body = $body;
        }



        public function send(): void{
            http_response_code($this->statusCode);
            foreach($this->headers as $name => $value){
                header("$name: $value");
            }

            echo $this->body;
        }

        public function getStatusCode(): int{
            return $this->statusCode;
        }
        public function getBody(): string{
            return $this->body;
        }

        public function getHeaders(): array{
            return $this->headers;
        }
    }