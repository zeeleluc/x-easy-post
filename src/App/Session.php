<?php
namespace App;

class Session
{
    private array $session;

    public function __construct()
    {
        $this->session = $_SESSION;
    }

    public function retrieve(): array
    {
        return $this->session;
    }

    public function getItem($name): mixed
    {
        if (true === array_key_exists($name, $_SESSION)) {
            return $_SESSION[$name];
        }

        return null;
    }

    public function setSession(string $name, mixed $value): Session
    {
        $_SESSION[$name] = $value;

        return $this;
    }

    public function destroySession(string $name): Session
    {
        if (true === array_key_exists($name, $_SESSION)) {
            unset($_SESSION[$name]);
        }

        return $this;
    }
}
