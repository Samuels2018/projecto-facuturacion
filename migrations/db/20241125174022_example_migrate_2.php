<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Template\BaseMigrate;

final class ExampleMigrate2 extends BaseMigrate
{
    function up(): void
    {
        $databases = $this->getDatabase($this->get_db_licencias());

        foreach ($databases as $db) {
            $pdo = $this->get_pdo($db);
            $pdo->exec("CREATE TABLE example_table2 (
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
            $pdo->exec("DROP TABLE IF EXISTS example_table2;");

            echo "Tabla eliminada en la base de datos: {$db['dbname']}\n";
        }
    }
}
