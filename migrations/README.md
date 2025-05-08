# Migraciones de Factuguay

## Requerimientos

* Tener php8.1 o superior
* Tener instalado composer
* Tener el path en el caso de windows php y composer 


## Instalacion inicial

``` composer install ```


## Comandos

### Todos los comandos se deben ejecutar en la carpeta migrations 


Crear migracion : 

``` php vendor/bin/phinx create ExampleMigrate ```

Ejecutar migracion: 

``` php vendor/bin/phinx migrate ```


### Luego de crear migraciones

1. Se debe cambiar la migracion generada en la carpeta db/ agregando la clase base de migracion


``` use Template\BaseMigrate; ```

2. luego agregar los metodos up y down para ejecutar la migracion y extender la clase BaseMigrate.

3. Y por ultimo especificar la base de datos con los metodos predefinidos, get_db_facturas, get_db_licencias, get_db_logs y get_db_utilidades


#### Ejemplo:

```php
<?php

declare(strict_types=1);

use Template\BaseMigrate;

final class ExampleMigrate extends BaseMigrate
{
   
    function up(): void
    {
        $databases = $this->getDatabase($this->get_db_licencias());

        foreach ($databases as $db) {
            $pdo = $this->get_pdo($db);
            $pdo->exec("CREATE TABLE example_table (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255),
                department VARCHAR(100),
                salary DECIMAL(10, 2)
            );");
        
            echo "Migración ejecutada en la base de datos: {$db['dbname']}\n";
        }
    }

    public function down(): void
    {
        $databases = $this->getDatabase($this->get_db_licencias());

        foreach ($databases as $db) {
      
            $pdo = $this->get_pdo($db);
            $pdo->exec("DROP TABLE IF EXISTS example_table;");

            echo "Tabla eliminada en la base de datos: {$db['dbname']}\n";
        }
    }

}


```

##### @author: Armando Rojas <armando.develop@gmail.com>