<?php
declare(strict_types=1);

namespace DB;

use Template\BaseMigrate;


final class ExampleMigration extends BaseMigrate
{
    
    function up(): void
    {
        $databases = $this->getDatabase($this->get_db_licencias());

        foreach ($databases as $db) {
            $pdo = $this->get_pdo($db);
            $pdo->exec("CREATE TABLE  example_table;");
        
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
