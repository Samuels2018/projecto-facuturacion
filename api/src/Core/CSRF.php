<?php

namespace App\Core;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;

class CSRF
{
    private $csrfTokenManager;
    private $session;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $this->session = new Session(new NativeSessionStorage());
            $this->session->start();
        } else {
            $this->session = new Session(new NativeSessionStorage());
        }
        $this->csrfTokenManager = new CsrfTokenManager();
    }

    public function get_csrf_token()
    {
        $csrfToken = $this->csrfTokenManager->getToken('form')->getValue();
        return $csrfToken;
    }

    public function validate_csrf_token($token)
    {
        $csrfToken = new CsrfToken('form', $token);

        if (!$this->csrfTokenManager->isTokenValid($csrfToken)) {
            throw new \Exception("CSRF TOKEN Inválido", 400);
        }

        return true;
    }
}
