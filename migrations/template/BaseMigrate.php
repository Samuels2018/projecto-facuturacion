<?php

namespace Template;

use Phinx\Migration\AbstractMigration;
use Dotenv\Dotenv;

class BaseMigrate extends AbstractMigration
{
    protected function load_env(){
        
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'  );
        $dotenv->load();

        return $_ENV;
    }

    public function get_pdo($db){
        $pdo = new \PDO(
            "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8mb4",
            $db['user'],
            $db['password']
        );

        return $pdo;
    }
  
    protected function getDatabase($name): array
    {
        $env = $this->load_env();

        if($name == "logs")

            return [
                [
                    'host' => $env['DB_HOST_LOG'],
                    'dbname' => $env['DB_NAME_LOG'],
                    'user' => $env['DB_USER_LOG'],
                    'password' => $env['DB_PASS_LOG'],
                ]
            ];
        else if($name == 'licencias')
            return [
                [
                    'host' => $env['DB_HOST_PLATAFORMA'],
                    'dbname' => $env['DB_NAME_PLATAFORMA'],
                    'user' => $env['DB_USER_PLATAFORMA'],
                    'password' => $env['DB_PASS_PLATAFORMA'],
                ]
            ];
        else if($name == 'utilidades')
        return [
            [
                'host' => $env['DB_HOST_UTILIDADES'],
                'dbname' => $env['DB_NAME_UTILIDADES'],
                'user' => $env['DB_USER_UTILIDADES'],
                'password' => $env['DB_PASS_UTILIDADES'],
            ]
        ];        
        else if($name == 'facturacion')
        return [
            [
                'host' => $env['DB_HOST_FACTURACION'],
                'dbname' => $env['DB_NAME_FACTURACION'],
                'user' => $env['DB_USER_FACTURACION'],
                'password' => $env['DB_PASS_FACTURACION'],
            ]
        ];
        else 
            return [];
    }


    public function get_db_licencias(){
        return 'licencias';
     
    }

    public function get_db_logs(){
        return 'logs';
    }

    public function get_db_utilidades(){
        return 'utilidades';
    }

    public function get_db_facturacion(){
        return 'facturacion';
   
    }
}

