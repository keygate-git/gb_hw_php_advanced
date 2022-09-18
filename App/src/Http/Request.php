<?php

namespace Student\App\Http;

use Student\App\Exceptions\HttpExeption;
use Student\App\Exceptions\JsonException;

class Request
{
    private array $get;
    private array $server;
    private string $body;

    public function __construct(array $get, array $server, string $body)
    {
        $this->get = $get;
        $this->server = $server;
        $this->body = $body;
    }

    public function path(): string 
    {
        if (!array_key_exists('REQUEST_URI', $this->server)) {
            throw new HttpExeption('Cannot get path from request 1');
        }

        $components = parse_url($this->server['REQUEST_URI']);

        if (!is_array($components) || !array_key_exists('path', $components)) {
            throw new HttpExeption ('Cannot get path from the request');
        }

        return $components['path'];
    }

    public function query(string $param): string 
    {
        if (!array_key_exists($param, $this->get)) {
            throw new HttpExeption('No such query param in the request: $param');
        }

        $value = trim($this->get[$param]);

        if(empty($value)) {
            throw new HttpExeption('Empty query param in the request: $param');
        }

        return $value;
    }

    public function header(string $header): string 
    {
        $headerName = mb_strtoupper("http_". str_replace('-', '_', $header));

        if (!array_key_exists($headerName, $this->server)) {
            throw new HttpExeption('No such header in the request: $header');
        }

        $value = trim($this->server[$headerName]);

        if (empty($value)) {
            throw new HttpExeption('Empty header in the request: $header');
        }

        return $value;
    }

    public function jsonBody(): array 
    {
        try {
            $data = json_decode($this->body, true, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new HttpExeption('Cannot decode json body');
        }

        if (!is_array($data)) {
            throw new HttpExeption('Not an array/object in json body');
        }
        
        return $data;
    }

    public function jsonBodyField(string $field): string
    {
        $data = $this->jsonBody();

        if (!array_key_exists($field, $data)) {
            throw new HttpException('No such field: $field');
        }

        if (empty($data[$field])) {
            throw new HttpExeption('Empty field: $field');
        }
        
        return $data[$field];
    }

    public function method(): string 
    {
        if (!array_key_exists('REQUEST_METHOD', $this->server)) {
            throw new HttpExeption('Cannot get method from request');
        }

        return $this->server['REQUEST_METHOD'];
    }
}