<?php
namespace App;

class Request
{
    private array $get;

    private array $post;

    private string $requestMethod;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        if (!$this->isCli()) {
            $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        }
    }

    public function get(): array
    {
        return $this->get;
    }

    public function post(): array
    {
        return $this->post;
    }

    public function isCli(): bool
    {
        return is_cli();
    }

    public function isPost(): bool
    {
        return ('post' === strtolower($_SERVER['REQUEST_METHOD']));
    }

    public function isGet(): bool
    {
        return ('get' === strtolower($_SERVER['REQUEST_METHOD']));
    }

    public function getParam($param): ?string
    {
        if (isset($this->get[$param])) {
            return $this->get[$param];
        }

        return null;
    }

    public function getPostParam($param): mixed
    {
        if (isset($this->post[$param])) {
            return $this->post[$param];
        }

        return null;
    }
}
