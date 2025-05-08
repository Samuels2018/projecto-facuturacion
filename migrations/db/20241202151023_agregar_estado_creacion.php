<?php

declare(strict_types=1);

use Template\BaseMigrate;

final class AgregarEstadoCreacion extends BaseMigrate
{
    function up(): void
    {
        $databases = $this->getDatabase($this->get_db_licencias());

        foreach ($databases as $db) {
            $pdo = $this->get_pdo($db);
            $pdo->exec("
                ALTER TABLE `usuarios` 
                ADD COLUMN `creacion_estado` varchar(1) 
                NULL  DEFAULT '3' COMMENT '1 = Por verificar , 2=Por Configurar empresa,
                 3=empresa configurada' AFTER `correo_temporal`;
            ");
        
            echo "Migración ejecutada en la base de datos: {$db['dbname']}\n";
        }
    }

    public function down(): void
    {

    }
}
