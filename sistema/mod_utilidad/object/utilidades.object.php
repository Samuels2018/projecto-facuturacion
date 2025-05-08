<?php

/********************************************************
 * 
 * 
 *    Vamos a meter aqui todas las funciones que tienen que ver con diccionario
 *    Por favor no repetir funciones en los object generales!!!!
 *    por ejemplo diccionario moneda en albaran, cotiacion, etc 
 * 
 *      Por favor no hacer consultas y enviar de vuelta el FETCH ALL esto hace que cada vez que necesites un resultado obliguemos a la BD
 *      Es mejor devolverlo en un array y tenerlo en memoria por si mas abajo en el codigo lo necesitas nuevamente
 */




 



class Utilidades
{

    private $db;
    public  $diccionario_moneda;
    public  $fk_entidad;

    public $estilos_bootstrap; // Array
    public $obtener_estados_verifactu;  // Array 
    public $diccionario_transacciones_documentos;  // Array 
    public $diccionario_transacciones_documentos_traductor; // Array 

    public $meses;

    public function __construct($db, $entidad = 1)
    {
        $this->db = $db;
        $this->fk_entidad = $entidad;

        $this->meses = [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ];
    }


    /// Para los diferentes botones y demas cuando es configurable por ejemplo una etiqueta 
    // Se usa en el CRM general 

    public function obtener_estilos_bootstrap()
    {

        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_estilos_bootstrap order by rowid ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        while ($datos =  $db->fetch(PDO::FETCH_OBJ)) {
            $this->estilos_bootstrap[$datos->rowid]['estilo'] = $datos->estilo;
            $this->estilos_bootstrap[$datos->rowid]['activo'] = $datos->activo;
        }

        return $this->estilos_bootstrap;
    }




    public function obtener_paises()
    {

        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_paises order by nombre ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }



    public function obtener_estados_verifactu()
    {

        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_factura_europa_verifactu_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();


        while ($obj = $db->fetch(PDO::FETCH_OBJ)) {
            $this->obtener_estados_verifactu[$obj->rowid]['etiqueta']  = $obj->etiqueta;
            $this->obtener_estados_verifactu[$obj->rowid]['class']   = $obj->class;
        }
    }




    public function obtener_diccionario_transacciones_documentos()
    {
        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_transacciones_documentos where activo  = 1 ";

        $db = $this->db->prepare($sql);
        $db->execute();
        while ($obj = $db->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_transacciones_documentos[$obj->rowid]['tabla']       = $obj->tabla;
            $this->diccionario_transacciones_documentos[$obj->rowid]['activo']      = $obj->activo;
            $this->diccionario_transacciones_documentos[$obj->rowid]['actor']       = $obj->actor;
            $this->diccionario_transacciones_documentos[$obj->rowid]['estilo']      = $obj->estilo;
            $this->diccionario_transacciones_documentos[$obj->rowid]['descripcion'] = $obj->descripcion;
            $this->diccionario_transacciones_documentos_traductor[$obj->tabla]['descripcion'] = $obj->descripcion;
        }

        return $this->diccionario_transacciones_documentos;
    }





    public function obtener_comunidades_autonomas($fk_pais)
    {
        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas WHERE fk_pais = $fk_pais order by nombre ASC";

        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtener_provincias($fk_comunidad_autonoma)
    {
        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias WHERE fk_comunidad_autonoma = $fk_comunidad_autonoma order by provincia ASC";

        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }


    public function obtener_municipios($fk_provincia)
    {
        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias_municipios WHERE fk_provincia = $fk_provincia order by municipio ASC";

        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtener_ubigeo_seleccionado($direccion_fk_provincia)
    {
        $sql = "
            SELECT 
                p.id AS provincia_id,
                p.provincia AS nombre_provincia,
                ca.id AS comunidad_autonoma_id,
                ca.nombre AS nombre_comunidad_autonoma,
                pais.rowid AS pais_id,
                pais.nombre AS nombre_pais,
                NULL AS municipio_id,
                NULL AS nombre_municipio,
                1 AS selected_provincia,
                1 AS selected_comunidad_autonoma,
                1 AS selected_pais
            FROM 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p
            JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas ca ON p.fk_comunidad_autonoma = ca.id
            JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_paises pais ON ca.fk_pais = pais.rowid
            WHERE 
                p.id = :direccion_fk_provincia

            UNION ALL

            SELECT 
                p.id AS provincia_id,
                p.provincia AS nombre_provincia,
                ca.id AS comunidad_autonoma_id,
                ca.nombre AS nombre_comunidad_autonoma,
                pais.rowid AS pais_id,
                pais.nombre AS nombre_pais,
                m.id AS municipio_id,
                m.municipio AS nombre_municipio,
                1 AS selected_provincia,
                1 AS selected_comunidad_autonoma,
                1 AS selected_pais
            FROM 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p
            JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas ca ON p.fk_comunidad_autonoma = ca.id
            JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_paises pais ON ca.fk_pais = pais.rowid
            LEFT JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias_municipios m ON m.fk_provincia = p.id
            WHERE 
                p.id = :direccion_fk_provincia

            UNION ALL

            SELECT 
                NULL AS provincia_id,
                NULL AS nombre_provincia, 
                ca.id AS comunidad_autonoma_id,
                ca.nombre AS nombre_comunidad_autonoma,
                pais.rowid AS pais_id,
                pais.nombre AS nombre_pais,
                NULL AS municipio_id,
                NULL AS nombre_municipio,
                0 AS selected_provincia, 
                CASE 
                    WHEN ca.id = (
                        SELECT ca_sub.id 
                        FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p_sub
                        JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas ca_sub ON p_sub.fk_comunidad_autonoma = ca_sub.id
                        WHERE p_sub.id = :direccion_fk_provincia
                    ) THEN 1 
                    ELSE 0 
                END AS selected_comunidad_autonoma,
                1 AS selected_pais
            FROM 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas ca
            JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_paises pais ON ca.fk_pais = pais.rowid
            WHERE 
                ca.fk_pais = (
                    SELECT ca_sub.fk_pais 
                    FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p_sub 
                    JOIN " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas ca_sub ON p_sub.fk_comunidad_autonoma = ca_sub.id
                    WHERE p_sub.id = :direccion_fk_provincia
                )

            UNION ALL

            SELECT 
                p.id AS provincia_id,
                p.provincia AS nombre_provincia,
                NULL AS comunidad_autonoma_id,
                NULL AS nombre_comunidad_autonoma,
                NULL AS pais_id,
                NULL AS nombre_pais,
                NULL AS municipio_id,
                NULL AS nombre_municipio,
                CASE 
                    WHEN p.id = :direccion_fk_provincia THEN 1 
                    ELSE 0 
                END AS selected_provincia,
                0 AS selected_comunidad_autonoma, 
                0 AS selected_pais 
            FROM 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p
            WHERE 
                p.fk_comunidad_autonoma = (
                    SELECT p_sub.fk_comunidad_autonoma 
                    FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p_sub
                    WHERE p_sub.id = :direccion_fk_provincia
                )

            UNION ALL

            SELECT 
                p.id AS provincia_id,
                p.provincia AS nombre_provincia,
                NULL AS comunidad_autonoma_id,
                NULL AS nombre_comunidad_autonoma,
                NULL AS pais_id,
                NULL AS nombre_pais,
                m.id AS municipio_id,
                m.municipio AS nombre_municipio,
                CASE 
                    WHEN p.id = :direccion_fk_provincia THEN 1 
                    ELSE 0 
                END AS selected_provincia,
                0 AS selected_comunidad_autonoma, 
                0 AS selected_pais 
            FROM 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p
            LEFT JOIN 
                " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias_municipios m ON m.fk_provincia = p.id
            WHERE 
                p.fk_comunidad_autonoma = (
                    SELECT p_sub.fk_comunidad_autonoma 
                    FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_comunidades_autonomas_provincias p_sub
                    WHERE p_sub.id = :direccion_fk_provincia
            );
        ";

        $db = $this->db->prepare($sql);
        $db->bindParam(':direccion_fk_provincia', $direccion_fk_provincia, PDO::PARAM_INT);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }


    public function obtener_formas_pago()
    {
        $entidad = $_SESSION['Entidad'];

        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_formas_pago WHERE activo = 1 and entidad = $entidad GROUP BY rowid ORDER BY label ASC";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtener_identificaciones_fiscales()
    {
        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_identificacion_fiscal WHERE activo = 1 AND borrado = 0 ORDER BY descripcion ASC";

        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }

    public function obtener_tipo_residencias()
    {
        $sql = "SELECT * FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_tipo_residencia WHERE activo = 1 AND borrado = 0 ORDER BY descripcion ASC";

        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }





    /******************************************************
     * 
     * 
     * 
     *   Aplicado y normalizado en 
     * 
     *   CISMA Cotizaciones 
     * 
     */

    public function obtener_monedas()
    {
        $sql = "select * from diccionario_monedas where 1 and entidad = :entidad";

        $db = $this->db->prepare($sql);
        $db->bindValue(':entidad', $_SESSION['Entidad'], PDO::PARAM_INT);
        $db->execute();

        while ($obj = $db->fetch(PDO::FETCH_OBJ)) {
            $this->diccionario_moneda[$obj->rowid]['etiqueta']  = $obj->etiqueta;
            $this->diccionario_moneda[$obj->rowid]['simbolo']   = $obj->simbolo;
            $this->diccionario_moneda[$obj->rowid]['codigo']    = $obj->codigo;
        }

        return $this->diccionario_moneda;
    }



    public function obtener_bancos()
    {
        $sql = "select * from diccionario_bancos where  entidad = :entidad and activo = 1 AND borrado = 0";
        $db = $this->db->prepare($sql);
        $db->bindValue(":entidad", $_SESSION['Entidad'], PDO::PARAM_INT);
        $db->execute();

        return $db->fetchAll(PDO::FETCH_OBJ);
    }




    public function obtener_estados_albaran_compra()
    {
        $sql = "SELECT  etiqueta AS etiqueta , class, rowid  FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_albarenes_compra_europa_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtener_estados_albaran_venta()
    {
        $sql = "SELECT   etiqueta AS etiqueta , class, rowid  FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_albarenes_venta_europa_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtener_estados_factura()
    {
        $sql = "SELECT  etiqueta AS etiqueta , class, rowid  FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_factura_europa_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtener_estados_compra()
    {
        $sql = "SELECT  etiqueta AS etiqueta , class, rowid  FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_compra_europa_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtener_estados_pedido()
    {
        $sql = "SELECT   etiqueta AS etiqueta , class, rowid  FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_pedidos_europa_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }
    public function obtener_estados_presupuesto()
    {
        $sql = "SELECT  etiqueta AS etiqueta , class, rowid  FROM " . DB_NAME_UTILIDADES_APOYO . ".diccionario_presupuesto_europa_diccionario ";
        $db = $this->db->prepare($sql);
        $db->execute();
        return $db->fetchAll(PDO::FETCH_OBJ);
    }


    public function obtenerRangoTrimestre($fecha = 'now')
    {
        $fechaActual = new DateTime($fecha);

        $mes = (int) $fechaActual->format('n');
        $año = (int) $fechaActual->format('Y');


        // Determinar el trimestre actual
        if ($mes >= 1 && $mes <= 3) {
            $inicioTrimestre = new DateTime("$año-01-01");
            $finTrimestre = new DateTime("$año-03-31");
        } elseif ($mes >= 4 && $mes <= 6) {
            $inicioTrimestre = new DateTime("$año-04-01");
            $finTrimestre = new DateTime("$año-06-30");
        } elseif ($mes >= 7 && $mes <= 9) {
            $inicioTrimestre = new DateTime("$año-07-01");
            $finTrimestre = new DateTime("$año-09-30");
        } else {
            $inicioTrimestre = new DateTime("$año-10-01");
            $finTrimestre = new DateTime("$año-12-31");
        }

        return [
            'inicio' => $inicioTrimestre->format('Y-m-d'),
            'fin' => $finTrimestre->format('Y-m-d')
        ];
    }



    function obtenerRangoMes($mes, $anio = null)
    {
        // Si no se pasa un año, tomamos el actual
        if ($anio === null) {
            $anio = date("Y");
        }

        // Obtener el primer día del mes
        $primerDia = date("Y-m-d", strtotime("$anio-$mes-01"));

        // Obtener el último día del mes
        $ultimoDia = date("Y-m-t", strtotime($primerDia));

        return [
            "primer_dia" => $primerDia,
            "ultimo_dia" => $ultimoDia
        ];
    }

public function nombre_publico_tabla($tabla){ 
    return  ucfirst(str_replace("fi_europa_", "", $tabla)); 
}
} // fin de la clase 
