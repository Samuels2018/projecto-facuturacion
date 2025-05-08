<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Capsule\Manager as Capsule;


class SistemaEmpresaLicencia extends Model
{
 
    protected $table = 'sistema_empresa_licencias';

    protected $connection = 'licencias'; 


    protected $primaryKey = 'rowid';

   
    public $incrementing = true;


    public $timestamps = false;

   
    protected $fillable = [
        'user',
        'pass',
        'bd',
        'server',
    ];

    public function create_database($archivoSql )
    {
        try {
            $nombre = $this->bd;
            $host = $this->server;
            $user = $this->user;
            $password = $this->pass;

          
            $resultado = Capsule::connection('licencias')->select("SHOW DATABASES LIKE '%{$nombre}%'" );


            if (empty($resultado)) {
                // Si la base de datos no existe, crearla
                $comandoCrear = "mysql -h {$host} -u {$user} -p'{$password}' -e 'CREATE DATABASE {$nombre};'";
                $outputCrear = null;
                $resultCodeCrear = null;
                exec($comandoCrear, $outputCrear, $resultCodeCrear);
    
                if ($resultCodeCrear !== 0) {
                    throw new \Exception("Hubo un problema al crear la base de datos '{$nombre}'.");
                }
    
                //echo "La base de datos '{$nombre}' se creó correctamente.\n";
            } else {
                // echo "La base de datos '{$nombre}' ya existe.\n";
            }

     
            $comando = "mysql -h {$host} -u {$user} -p'{$password}' --host=localhost {$nombre} < {$archivoSql}";

            $output = null;
            $resultCode = null;
            exec($comando, $output, $resultCode);

            if ($resultCode === 0) {
              //  echo "La base de datos '{$nombre}' se restauró correctamente desde el archivo {$archivoSql}.\n";
                return true;
            } else {
                throw new \Exception("Hubo un problema al restaurar la base de datos '{$nombre}' desde el archivo.");
            }
        } catch (\Exception $e) {
           
            echo "Error: " . $e->getMessage() . "\n";
            return false;
        }
    }
       
     

   
    // public function empresas()
    // {
    //     return $this->hasMany(SistemaEmpresa::class, 'fk_sistema_empresa_licencias');
    // }
}
