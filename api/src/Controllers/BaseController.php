<?php


namespace App\Controllers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Core\{Env, CSRF};

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\CsrfToken;


class BaseController
{
    protected $twig;
    protected $env;
    protected $csrf;

    public function __construct() {

        $this->env = new Env();

        $loader = new FilesystemLoader('../src/views');
        $this->twig = new Environment($loader, [
            //'cache' => '../src/cache', 
            'cache' => false, 
        ]);

        $this->csrf = new CSRF();

    }

    public function render($template , $params = []){
        $params['csrf'] =  $this->get_csrf_token();

        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        $baseUrl = $scheme . '://' . $_SERVER['HTTP_HOST'];

        $params['base_url']  = $baseUrl;

        return $this->twig->render($template . '.twig', $params);
    }

    public function get_env($key){
        return $this->env->get($key);
    }

    public function get_csrf_token(){
        return $this->csrf->get_csrf_token();
    }

    function validate_csrf_token($token){
        
        $control = $this->csrf->validate_csrf_token($token);
        if(!$control) throw new \Exception("CSRF TOKEN Invalido", 400);
        
    }

    public function response($response , $code = 200){

        return json_encode([
            'data' => $response,
            'code' => $code
        ]);
    }

    public function handle_errors($e){

        $code = $e->getCode() > 500 ?  500 : $e->getCode();

        $message = $e->getMessage();
        $message_json = json_decode($message);

        $response = [
            'errors' => $message_json ?? $message
        ];

        return [  'response' => $response ,  'code' =>  $code];
    }

  
 
}

