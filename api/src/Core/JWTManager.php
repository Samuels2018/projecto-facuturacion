<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Core\Env;
use Exception;

class JWTManager
{
    private $secretKey;
    private $algorithm;

    public function __construct($algorithm = 'HS256')
    {
        $env = new Env();
        $this->secretKey = $env->get('JWT_SECRET');
        $this->algorithm = $algorithm;
    }

    public function encode(array $data = [])
    {
        $expiration = 3600;
        $payload = [
            'iss' => 'http://example.org',            // Emisor
            'aud' => 'http://example.com',            // Audiencia
            'iat' => time(),                          // Hora de emisión
            'nbf' => time(),                          // No antes de este tiempo
            'exp' => time() + $expiration,            // Tiempo de expiración
            'data' => $data                           // Datos personalizados
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function decode($token)
    {
        return JWT::decode($token, new Key($this->secretKey, $this->algorithm));
       
    }
}
