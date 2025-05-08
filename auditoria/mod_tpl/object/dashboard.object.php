<?php

include_once(ENLACE_SERVIDOR . "mod_seguridad/object/seguridad.object.php");

class Dashboard extends  Seguridad
{

    private $db;
    public  $entidad;

    // Función __construct que acepta una conexión a la base de datos
    public function __construct($db, $entidad)
    {
        $this->db = $db;

        if (empty($entidad)) {
            echo "Debe indicarse la entidad antes de continuar ";
            exit(1);
        }
        parent::__construct();  // Esto inicializa la clase SEGURIDAD

        $this->entidad = $entidad;
    } // Funcion Constructor





    public function devolver_datos_dashboard($filtro_mes, $filtro_anio)
    {

        $retorno = array(
            'totales_ventas' => array(
                'ventas' => 0,
                'a_cobrar' => 0,
                'vencido' => 0,
                'vencido_porcentaje' => 0
            )
        );

        $sql = "SELECT FORMAT(SUM(CASE WHEN estado = 1 THEN total ELSE 0 END), 2) AS ventas,
                    FORMAT(SUM(CASE 
                        WHEN estado_pagada = 0 THEN (total - pagado)
                            ELSE 0
                        END), 2) AS a_cobrar,
                        FORMAT(SUM(CASE 
                            WHEN estado_pagada = 0 AND fecha_vencimiento < CURDATE() THEN (total - pagado)
                            ELSE 0
                        END), 2) AS vencido,
                    FORMAT(
                        (SUM(CASE 
                            WHEN estado_pagada = 0 AND fecha_vencimiento < CURDATE() THEN (total - pagado)
                            ELSE 0
                        END) / SUM(CASE WHEN estado = 1 THEN total ELSE 0 END)) * 100, 2
                    ) AS 'vencido_porcentaje'
                FROM
                    fi_europa_facturas
                WHERE
                    borrado = 0 -- Excluir facturas borradas
                    AND MONTH(fecha) = :filtro_mes -- Filtrar por el mes especificado
                    AND YEAR(fecha) = :filtro_anio -- Filtrar por el año especificado
                    AND entidad = :entidad;
        ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->bindValue(":filtro_mes",  $filtro_mes);
        $db->bindValue(":filtro_anio",  $filtro_anio);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        $retorno['totales_ventas']['ventas'] = $row['ventas'] ?? 0;
        $retorno['totales_ventas']['a_cobrar'] = $row['a_cobrar'] ?? 0;
        $retorno['totales_ventas']['vencido'] = $row['vencido'] ?? 0;
        $retorno['totales_ventas']['vencido_porcentaje'] = $row['vencido_porcentaje'] ?? 0;

        return $retorno;
        exit;
    }
    public function devolver_datos_dashboard_fecha($filtro_desde, $filtro_hasta)
    {

        $retorno = array(
            'totales_ventas' => array(
                'ventas' => 0,
                'a_cobrar' => 0,
                'vencido' => 0,
                'vencido_porcentaje' => 0
            )
        );

        $sql = "SELECT FORMAT(SUM(CASE WHEN estado = 1 THEN total ELSE 0 END), 2) AS ventas,
                    FORMAT(SUM(CASE 
                        WHEN estado_pagada = 0 THEN (total - pagado)
                            ELSE 0
                        END), 2) AS a_cobrar,
                        FORMAT(SUM(CASE 
                            WHEN estado_pagada = 0 AND fecha_vencimiento < CURDATE() THEN (total - pagado)
                            ELSE 0
                        END), 2) AS vencido,
                    FORMAT(
                        (SUM(CASE 
                            WHEN estado_pagada = 0 AND fecha_vencimiento < CURDATE() THEN (total - pagado)
                            ELSE 0
                        END) / SUM(CASE WHEN estado = 1 THEN total ELSE 0 END)) * 100, 2
                    ) AS 'vencido_porcentaje'
                FROM
                    fi_europa_facturas
                WHERE
                    borrado = 0 -- Excluir facturas borradas
                    AND fecha BETWEEN :fecha_desde AND :fecha_hasta -- Filtrar por rango de fechas
                    AND entidad = :entidad;
        ";

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->bindValue(":fecha_desde",  $filtro_desde);
        $db->bindValue(":fecha_hasta",  $filtro_hasta);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        $retorno['totales_ventas']['ventas'] = $row['ventas'] ?? 0;
        $retorno['totales_ventas']['a_cobrar'] = $row['a_cobrar'] ?? 0;
        $retorno['totales_ventas']['vencido'] = $row['vencido'] ?? 0;
        $retorno['totales_ventas']['vencido_porcentaje'] = $row['vencido_porcentaje'] ?? 0;

        return $retorno;
        exit;
    }
    public function devolver_datos_dashboard_anio($filtro_anio)
    {

        $retorno = array(
            'totales_ventas' => array(
                'ventas' => 0,
                'a_cobrar' => 0,
                'vencido' => 0,
                'vencido_porcentaje' => 0
            )
        );

        $sql = "SELECT FORMAT(SUM(CASE WHEN estado = 1 THEN total ELSE 0 END), 2) AS ventas,
                    FORMAT(SUM(CASE 
                        WHEN estado_pagada = 0 THEN (total - pagado)
                            ELSE 0
                        END), 2) AS a_cobrar,
                        FORMAT(SUM(CASE 
                            WHEN estado_pagada = 0 AND fecha_vencimiento < CURDATE() THEN (total - pagado)
                            ELSE 0
                        END), 2) AS vencido,
                    FORMAT(
                        (SUM(CASE 
                            WHEN estado_pagada = 0 AND fecha_vencimiento < CURDATE() THEN (total - pagado)
                            ELSE 0
                        END) / SUM(CASE WHEN estado = 1 THEN total ELSE 0 END)) * 100, 2
                    ) AS 'vencido_porcentaje'
                FROM
                    fi_europa_facturas
                WHERE
                    borrado = 0 -- Excluir facturas borradas
                    AND YEAR(fecha) = :fecha_anio -- Filtrar por rango de fechas
                    AND entidad = :entidad;
        ";

        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->bindValue(":fecha_anio",  $filtro_anio);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        $retorno['totales_ventas']['ventas'] = $row['ventas'] ?? 0;
        $retorno['totales_ventas']['a_cobrar'] = $row['a_cobrar'] ?? 0;
        $retorno['totales_ventas']['vencido'] = $row['vencido'] ?? 0;
        $retorno['totales_ventas']['vencido_porcentaje'] = $row['vencido_porcentaje'] ?? 0;

        return $retorno;
        exit;
    }
    public function devolver_datos_dashboard_verifactum()
    {

        $retorno = array(
            'verifactum_sin' => 0,
            'verifactum_con' => 0,
            'borrador' => 0,
            'validada' => 0
        );

        $sql = "SELECT FORMAT(SUM(CASE WHEN verifactum_produccion = 1 THEN 1 ELSE 0 END), 2) AS verifactum_con,
                    FORMAT(SUM(CASE WHEN verifactum_produccion = 0 THEN 1 ELSE 0 END), 2) AS verifactum_sin,
                    SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) AS borrador,
                    SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) AS validada
                FROM
                    fi_europa_facturas
                WHERE
                    borrado = 0 -- Excluir facturas borradas
                    AND entidad = :entidad;
        ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        $retorno['verifactum_sin'] = $row['verifactum_sin'] ?? 0;
        $retorno['verifactum_con'] = $row['verifactum_con'] ?? 0;
        $retorno['borrador'] = $row['borrador'] ?? 0;
        $retorno['validada'] = $row['validada'] ?? 0;

        return $retorno;
        exit;
    }


    public function devolver_datos_dashboard_series($filtro, $filtro_anio = null, $filtro_mes = null)
    {
        $retorno = array(
            'totales_ventas' => array()
        );

        $sql = "";
        $params = array(":entidad" => $_SESSION["Entidad"]);

        switch ($filtro) {
            case "anual": // Mostrar 12 años
                /*
                    $sql ="WITH ultimos_anios AS (
                            SELECT YEAR(CURDATE()) - n.year_num AS anio
                            FROM (
                                SELECT 0 AS year_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                                SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                                SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                            ) AS n
                        )
                        SELECT 
                            ua.anio AS periodo,
                            FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.total ELSE 0 END), 0), 2) AS ventas,
                            FORMAT(IFNULL(SUM(CASE 
                                WHEN f.estado_pagada = 0 THEN f.pagado
                                ELSE f.total
                            END), 0), 2) AS a_cobrar
                        FROM 
                            ultimos_anios ua
                        LEFT JOIN fi_europa_facturas f
                            ON YEAR(f.fecha) = ua.anio
                            AND f.borrado = 0
                            AND f.estado = 1
                            AND f.entidad = :entidad
                        GROUP BY 
                            ua.anio
                        ORDER BY 
                            ua.anio;
                    ";
                */
                $sql = "WITH ultimos_anios AS (
                        SELECT YEAR(CURDATE()) - n.year_num AS anio
                        FROM (
                            SELECT 0 AS year_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                        ) AS n
                    )
                    SELECT 
                        ua.anio AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.total ELSE 0 END), 0), 2) AS ventas,
                        FORMAT(IFNULL(SUM(fp.monto),0), 2) AS a_cobrar
                    FROM 
                        ultimos_anios ua
                    LEFT JOIN fi_europa_facturas f
                        ON YEAR(f.fecha) = ua.anio
                        AND f.borrado = 0
                        AND f.estado = 1
                        AND f.entidad = :entidad
                    LEFT JOIN fi_europa_facturas_pagos fp
                        ON YEAR(fp.fecha_pago) = ua.anio AND fp.fk_factura = f.rowid
                    GROUP BY 
                        ua.anio
                    ORDER BY 
                        ua.anio DESC;
                ";
                break;

            case "mensual": // Mostrar 12 meses
                /*
                $sql ="WITH ultimos_meses AS (
                        SELECT 
                            DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH), '%Y%m') AS periodo,
                            YEAR(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH)) AS anio,
                            MONTH(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH)) AS mes
                        FROM (
                            SELECT 0 AS month_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                        ) AS n
                    )
                    SELECT 
                        CASE um.mes 
                            WHEN 1 THEN 'Enero' 
                            WHEN 2 THEN 'Febrero' 
                            WHEN 3 THEN 'Marzo' 
                            WHEN 4 THEN 'Abril' 
                            WHEN 5 THEN 'Mayo' 
                            WHEN 6 THEN 'Junio' 
                            WHEN 7 THEN 'Julio' 
                            WHEN 8 THEN 'Agosto' 
                            WHEN 9 THEN 'Septiembre' 
                            WHEN 10 THEN 'Octubre' 
                            WHEN 11 THEN 'Noviembre' 
                            WHEN 12 THEN 'Diciembre' 
                        END AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.total ELSE 0 END), 0), 2) AS ventas,
                        FORMAT(IFNULL(SUM(fp.monto),0), 2) AS a_cobrar
                    FROM 
                        ultimos_meses um
                    LEFT JOIN fi_europa_facturas f
                        ON YEAR(f.fecha) = um.anio AND MONTH(f.fecha) = um.mes
                        AND f.borrado = 0
                        AND f.estado = 1
                        AND f.entidad = :entidad
                    LEFT JOIN fi_europa_facturas_pagos fp
                        ON YEAR(fp.fecha_pago) = um.anio AND MONTH(fp.fecha_pago) = um.mes AND fp.fk_factura = f.rowid
                    GROUP BY 
                        um.mes
                    ORDER BY 
                        um.mes;
                    ";
                */
                $sql = "WITH ultimos_meses AS (
                    SELECT 
                        DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n MONTH), '%Y%m') AS periodo,
                        YEAR(DATE_SUB(CURDATE(), INTERVAL n MONTH)) AS anio,
                        MONTH(DATE_SUB(CURDATE(), INTERVAL n MONTH)) AS mes
                    FROM (
                        SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                        SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                        SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                    ) AS nums
                    )
                    SELECT 
                    CONCAT(
                        CASE um.mes 
                            WHEN 1 THEN 'Enero' 
                            WHEN 2 THEN 'Febrero' 
                            WHEN 3 THEN 'Marzo' 
                            WHEN 4 THEN 'Abril' 
                            WHEN 5 THEN 'Mayo' 
                            WHEN 6 THEN 'Junio' 
                            WHEN 7 THEN 'Julio' 
                            WHEN 8 THEN 'Agosto' 
                            WHEN 9 THEN 'Septiembre' 
                            WHEN 10 THEN 'Octubre' 
                            WHEN 11 THEN 'Noviembre' 
                            WHEN 12 THEN 'Diciembre' 
                        END,
                        ' ',
                        um.anio
                    ) AS periodo,
                    FORMAT(IFNULL(SUM(CASE WHEN (f.entidad = :entidad) THEN f.total ELSE 0 END), 0), 2) AS ventas,
                    FORMAT(IFNULL(( 
                            SELECT SUM(fp.monto) 
                            FROM fi_europa_facturas_pagos fp 
                            WHERE fp.fk_factura IN 
                                ( SELECT f.rowid FROM fi_europa_facturas f WHERE YEAR(f.fecha) = um.anio AND MONTH(f.fecha) = um.mes AND f.borrado = 0 AND f.estado = 1 AND f.entidad = :entidad ) 
                            AND fp.entidad = :entidad
                            ), 0), 2) AS a_cobrar
                    FROM 
                    ultimos_meses um
                    LEFT JOIN fi_europa_facturas f
                    ON DATE_FORMAT(f.fecha, '%Y%m') = um.periodo
                    AND f.borrado = 0
                    AND f.estado = 1
                    AND f.entidad = :entidad
                    GROUP BY 
                    um.periodo, um.anio, um.mes
                    ORDER BY 
                    um.anio ASC, um.mes ASC;
                ";
                break;
            case "semanal": // Mostrar 12 semanas
                $sql = "WITH ultimas_semanas AS (
                        SELECT 
                            YEARWEEK(DATE_SUB(CURDATE(), INTERVAL n.week_num WEEK), 3) AS semana
                        FROM (
                            SELECT 0 AS week_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                        ) AS n
                    )
                    SELECT 
                        us.semana AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.total ELSE 0 END), 0), 2) AS ventas,
                            FORMAT(IFNULL(SUM(fp.monto),0), 2) AS a_cobrar
                    FROM 
                        ultimas_semanas us
                    LEFT JOIN fi_europa_facturas f
                        ON YEARWEEK(f.fecha, 3) = us.semana
                        AND f.borrado = 0
                        AND f.estado = 1
                        AND f.entidad = :entidad
                    LEFT JOIN fi_europa_facturas_pagos fp
                        ON YEARWEEK(fp.fecha_pago, 3) = us.semana AND fp.fk_factura = f.rowid
                    GROUP BY 
                        us.semana
                    ORDER BY 
                        us.semana;
                ";
                break;

            case "diario": // Mostrar los últimos 30 días
                /*
                    $sql = "WITH ultimos_dias AS (
                        SELECT 
                            DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n.day_num DAY), '%Y-%m-%d') AS fecha
                        FROM (
                            SELECT 0 AS day_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                            SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL
                            SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL
                            SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL
                            SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL
                            SELECT 28 UNION ALL SELECT 29
                        ) AS n
                    )
                    SELECT 
                        DAY(ud.fecha) AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.total ELSE 0 END), 0), 2) AS ventas,
                        FORMAT(IFNULL(SUM(CASE 
                            WHEN f.estado_pagada = 0 THEN f.pagado
                            ELSE f.total
                        END), 0), 2) AS a_cobrar
                    FROM 
                        ultimos_dias ud
                    LEFT JOIN fi_europa_facturas f
                        ON DATE(f.fecha) = ud.fecha
                        AND f.borrado = 0
                        AND f.estado = 1
                        AND f.entidad = :entidad
                    GROUP BY 
                        ud.fecha
                    ORDER BY 
                        ud.fecha;
                    ";
                */
                $sql = "WITH ultimos_dias AS (
                        SELECT 
                            DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n.day_num DAY), '%Y-%m-%d') AS fecha
                        FROM (
                            SELECT 0 AS day_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                            SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL
                            SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL
                            SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL
                            SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL
                            SELECT 28 UNION ALL SELECT 29
                        ) AS n
                    )
                    SELECT 
                        DAY(ud.fecha) AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.total ELSE 0 END), 0), 2) AS ventas,
                            FORMAT(IFNULL(SUM(fp.monto),0), 2) AS a_cobrar
                    FROM 
                        ultimos_dias ud
                    LEFT JOIN fi_europa_facturas f
                        ON DATE(f.fecha) = ud.fecha
                        AND f.borrado = 0
                        AND f.estado = 1
                        AND f.entidad = :entidad
                    LEFT JOIN fi_europa_facturas_pagos fp
                        ON DATE(fp.fecha_pago) = ud.fecha AND fp.fk_factura = f.rowid
                    GROUP BY 
                        ud.fecha
                    ORDER BY 
                        ud.fecha;
                ";
                break;

            default:
                return $retorno; // Retorna vacío si el filtro no es válido
        }

        // Ejecutar la consulta
        $db = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $db->bindValue($key, $value);
        }
        $db->execute();
        $rows = $db->fetchAll(PDO::FETCH_ASSOC);

        // Formatear resultados
        foreach ($rows as $row) {
            $periodo = $row['periodo'];
            $retorno['totales_ventas'][$periodo] = array(
                'ventas' => $row['ventas'] ?? 0,
                'a_cobrar' => $row['a_cobrar'] ?? 0
            );
        }

        return $retorno;
        exit;
    }
    public function devolver_datos_dashboard_series_base_iva($filtro, $filtro_anio = null, $filtro_mes = null)
    {
        $retorno = array(
            'totales_ventas' => array()
        );

        $sql = "";
        $params = array(":entidad" => $_SESSION["Entidad"]);

        switch ($filtro) {
            case "anual": // Mostrar 12 años
                $sql = "WITH ultimos_anios AS (
                            SELECT YEAR(CURDATE()) - n.year_num AS anio
                            FROM (
                                SELECT 0 AS year_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                                SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                                SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                            ) AS n
                        )
                        SELECT 
                            ua.anio AS periodo,
                            FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.subtotal_pre_retencion ELSE 0 END), 0), 2) AS ventas,
                            FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.IVA_0+f.IVA_10+f.IVA_4+f.IVA_21) ELSE 0 END), 0), 2) AS iva
                            -- FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.impuesto_iva) ELSE 0 END), 0), 2) AS iva
                        FROM 
                            ultimos_anios ua
                        LEFT JOIN fi_europa_facturas f
                            ON YEAR(f.fecha) = ua.anio
                            AND f.borrado = 0
                            AND f.entidad = :entidad
                        GROUP BY 
                            ua.anio
                        ORDER BY 
                            ua.anio;
                    ";
                break;

            case "mensual": // Mostrar 12 meses
                $sql ="WITH ultimos_meses AS (
                        SELECT 
                            DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH), '%Y%m') AS periodo,
                            YEAR(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH)) AS anio,
                            MONTH(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH)) AS mes,
                            DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH), '%M %Y') AS mes_anio
                        FROM (
                            SELECT 0 AS month_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                        ) AS n
                        WHERE DATE_SUB(CURDATE(), INTERVAL n.month_num MONTH) >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                        )
                        SELECT 
                            CONCAT(
                            CASE um.mes 
                                WHEN 1 THEN 'Enero' 
                                WHEN 2 THEN 'Febrero' 
                                WHEN 3 THEN 'Marzo' 
                                WHEN 4 THEN 'Abril' 
                                WHEN 5 THEN 'Mayo' 
                                WHEN 6 THEN 'Junio' 
                                WHEN 7 THEN 'Julio' 
                                WHEN 8 THEN 'Agosto' 
                                WHEN 9 THEN 'Septiembre' 
                                WHEN 10 THEN 'Octubre' 
                                WHEN 11 THEN 'Noviembre' 
                                WHEN 12 THEN 'Diciembre' 
                            END,
                            ' ',
                            um.anio
                        ) AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.subtotal_pre_retencion ELSE 0 END), 0), 2) AS ventas,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.IVA_0 + f.IVA_10 + f.IVA_4 + f.IVA_21) ELSE 0 END), 0), 2) AS iva
                        -- FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.impuesto_iva) ELSE 0 END), 0), 2) AS iva
                    FROM ultimos_meses um
                    LEFT JOIN fi_europa_facturas f ON YEAR(f.fecha) = um.anio AND MONTH(f.fecha) = um.mes
                        AND f.borrado = 0
                        AND f.entidad = :entidad
                    GROUP BY 
                        um.mes_anio
                    ORDER BY 
                        um.anio ASC, um.mes ASC;
                ";
                break;
            case "semanal": // Mostrar 12 semanas
                $sql = "WITH ultimas_semanas AS (
                        SELECT 
                            YEARWEEK(DATE_SUB(CURDATE(), INTERVAL n.week_num WEEK), 3) AS semana
                        FROM (
                            SELECT 0 AS week_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11
                        ) AS n
                    )
                    SELECT 
                        us.semana AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.subtotal_pre_retencion ELSE 0 END), 0), 2) AS ventas,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.IVA_0+f.IVA_10+f.IVA_4+f.IVA_21) ELSE 0 END), 0), 2) AS iva
                        -- FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.impuesto_iva) ELSE 0 END), 0), 2) AS iva
                    FROM 
                        ultimas_semanas us
                    LEFT JOIN fi_europa_facturas f
                        ON YEARWEEK(f.fecha, 3) = us.semana
                        AND f.borrado = 0
                        AND f.entidad = :entidad
                    GROUP BY 
                        us.semana
                    ORDER BY 
                        us.semana;
                ";
                break;

            case "diario": // Mostrar los últimos 30 días
                $sql = "WITH ultimos_dias AS (
                        SELECT 
                            DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n.day_num DAY), '%Y-%m-%d') AS fecha
                        FROM (
                            SELECT 0 AS day_num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
                            SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
                            SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
                            SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL
                            SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL
                            SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL
                            SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL
                            SELECT 28 UNION ALL SELECT 29
                        ) AS n
                    )
                    SELECT 
                        DAY(ud.fecha) AS periodo,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN f.subtotal_pre_retencion ELSE 0 END), 0), 2) AS ventas,
                        FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.IVA_0+f.IVA_10+f.IVA_4+f.IVA_21) ELSE 0 END), 0), 2) AS iva
                        -- FORMAT(IFNULL(SUM(CASE WHEN f.estado = 1 THEN (f.impuesto_iva) ELSE 0 END), 0), 2) AS iva
                    FROM 
                        ultimos_dias ud
                    LEFT JOIN fi_europa_facturas f
                        ON DATE(f.fecha) = ud.fecha
                        AND f.borrado = 0
                        AND f.entidad = :entidad
                    GROUP BY 
                        ud.fecha
                    ORDER BY 
                        ud.fecha;
                    ";
                break;

            default:
                return $retorno; // Retorna vacío si el filtro no es válido
        }

        // Ejecutar la consulta
        $db = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $db->bindValue($key, $value);
        }
        $db->execute();
        $rows = $db->fetchAll(PDO::FETCH_ASSOC);

        // Formatear resultados
        foreach ($rows as $row) {
            $periodo = $row['periodo'];
            $retorno['totales_ventas'][$periodo] = array(
                'ventas' => $row['ventas'] ?? 0,
                'iva' => $row['iva'] ?? 0
            );
        }

        return $retorno;
        exit;
    }
    public function devolver_productos_mas_vendidos($max = 10)
    {
        $sql = "SELECT 
                    p.rowid,
                    (CASE WHEN p.tipo = 1 THEN 'Producto' ELSE 'Servicio' END) AS tipo,
                    p.label as nombre, 
                    p.stock,
                    SUM(fd.precio_unitario) AS producto_precio,
                    SUM(fd.cantidad) AS producto_cantidad,
                    SUM(fd.precio_unitario * fd.cantidad) AS producto_venta
                FROM fi_europa_facturas f
                INNER JOIN fi_europa_facturas_detalle fd ON f.rowid = fd.fk_factura
                INNER JOIN fi_productos p ON fd.fk_producto = p.rowid
                WHERE
                    f.borrado = 0
                    AND f.estado = 1
                    AND f.entidad = :entidad
                GROUP BY p.rowid, tipo, p.label, p.stock
                ORDER BY tipo DESC
                LIMIT $max;
        ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->execute();
        $retorno = $db->fetchAll(PDO::FETCH_ASSOC);

        return $retorno;
        exit;
    }
    public function devolver_datos_dashboard_ultimas_facturas($max = 10)
    {
        $sql = "SELECT 
            f.rowid AS factura_id,
            f.referencia AS numero_serie,
            f.fecha,
            f.total AS importe,
            (CASE WHEN f.TotalDescuentos > 0 THEN 1 ELSE 0 END) AS tiene_descuento,
            CONCAT(u.nombre, ' ', u.apellidos) AS cliente_nombre,
            u.avatar AS cliente_avatar,
            u.rowid AS userid,
            SUM(fd.cantidad) AS cantidad,
            f.total AS valor_venta
        FROM 
            fi_europa_facturas f
        INNER JOIN 
            fi_europa_facturas_detalle fd ON f.rowid = fd.fk_factura
        INNER JOIN 
            fi_usuarios u ON f.fk_usuario_crear = u.rowid
        WHERE 
            f.borrado = 0
            AND f.estado = 1
            AND f.entidad = :entidad
        GROUP BY factura_id, numero_serie, fecha, importe, TotalDescuentos
        ORDER BY 
            f.fecha DESC
        LIMIT $max;
        ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->execute();
        $retorno = $db->fetchAll(PDO::FETCH_ASSOC);

        return $retorno;
        exit;
    }

    public function devolver_datos_dashboard_estados()
    {

        $retorno = array(
            'borrador' => 0,
            'pendiente' => 0,
            'correcto' => 0,
            'incorrecto' => 0
        );

        $sql = "SELECT FORMAT(SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END), 2) AS borrador,
                        FORMAT(SUM(CASE WHEN estado = 1 AND estado_hacienda = 1 THEN 1 ELSE 0 END), 2) AS pendiente,
                        FORMAT(SUM(CASE WHEN estado = 1 AND estado_hacienda = 2 THEN 1 ELSE 0 END), 2) AS incorrecto,
                        FORMAT(SUM(CASE WHEN estado = 1 AND estado_hacienda = 3 THEN 1 ELSE 0 END), 2) AS correcto
                FROM
                    fi_europa_facturas
                WHERE
                    borrado = 0 -- Excluir facturas borradas
                    AND entidad = :entidad;
        ";
        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad",  $_SESSION["Entidad"]);
        $db->execute();
        $row = $db->fetch(PDO::FETCH_ASSOC);

        $retorno['borrador'] = $row['borrador'] ?? 0;
        $retorno['pendiente'] = $row['pendiente'] ?? 0;
        $retorno['correcto'] = $row['correcto'] ?? 0;
        $retorno['incorrecto'] = $row['incorrecto'] ?? 0;

        return $retorno;
        exit;
    }
} // Fin Objeto 
