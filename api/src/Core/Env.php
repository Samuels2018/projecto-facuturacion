<?php 

    namespace App\Core; 

    use App\Core\Path;

    class Env{

        protected $env ;

        public function __construct() {

            $file_env = Path::$path_env;

            $this->load($file_env);

            $this->env = $_ENV;

        }

        public function get($key){
            return $this->env[$key];
        }

        protected function load($file) {

            if (!is_readable($file)) {
                throw new \InvalidArgumentException('No se puede leer el archivo .env');
            }
        
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue; 
                }
        
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
        
                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value)); 
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
        
        
    }