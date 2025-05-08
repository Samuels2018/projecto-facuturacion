<?php 

    namespace App\Core;
    
    use Illuminate\Database\Capsule\Manager as Capsule;
    use App\Core\Env;



    class DB{

        public static function init(){

            $env = new Env();


            $capsule = new Capsule;

    
            $capsule->addConnection([
                'driver'    => 'mysql',       
                'host'      =>  $env->get('DB_HOST_LOG'),    
                'database'  =>  $env->get('DB_NAME_LOG'),        
                'username'  => $env->get('DB_USER_LOG'),       
                'password'  =>  $env->get('DB_PASS_LOG'),    
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ], 'logs');


            $capsule->addConnection([
                'driver'    => 'mysql',       
                'host'      =>  $env->get('DB_HOST_PLATAFORMA'),    
                'database'  =>  $env->get('DB_NAME_PLATAFORMA'),        
                'username'  => $env->get('DB_USER_PLATAFORMA'),       
                'password'  =>  $env->get('DB_PASS_PLATAFORMA'),    
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ], 'licencias');

            // Configurar Eloquent como ORM
            $capsule->setAsGlobal();
            $capsule->bootEloquent();

        }
    }