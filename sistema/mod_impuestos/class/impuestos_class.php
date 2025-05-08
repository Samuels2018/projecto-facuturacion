<?php

if (!empty($_REQUEST['action'])) :

    session_start();
    include_once("../../conf/conf.php");

    require_once ENLACE_SERVIDOR.'mod_impuestos/object/impuestos_object.php';

    switch ($_REQUEST['action']):


     //aqui es para el modal cuando creamos o actualizamos un impuesto
    case "CrearActualizarImpuestos":
    
        $impuestos = new impuestos($dbh, $_SESSION['Entidad']);
        // Asignación de valores desde $_REQUEST con isset y valores por defecto.
        $impuestos->rowid = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

       

        // Solo asignar los valores si están presentes en la solicitud.
        if (isset($_REQUEST['impuesto'])) {
            $impuestos->impuesto = $_REQUEST['impuesto'];
        }

        if (isset($_REQUEST['recargo_equivalencia'])) {
            $impuestos->recargo_equivalencia = floatval($_REQUEST['recargo_equivalencia']);
        }

        if (isset($_REQUEST['impuesto_texto'])) {
            $impuestos->impuesto_texto = $_REQUEST['impuesto_texto'];
        }

        if (isset($_REQUEST['pais'])) {
            $impuestos->pais = $_REQUEST['pais'];
        }

        if (isset($_REQUEST['autogen'])) {
            $impuestos->autogen = intval($_REQUEST['autogen']);
        }

        // Verificación de rowid para decidir si crear o actualizar
        if ($impuestos->rowid > 0) {
            // Si rowid es distinto de 0, se llama a actualizar()
            $resultado = $impuestos->actualizar();
        } else {
            // Si rowid es 0, se llama a crear()
            $resultado = $impuestos->crear();
        }
            // Devolver el resultado en formato JSON
        echo json_encode($resultado);

      break;


    case 'EliminarImpuesto':
      	$impuestos = new impuestos($dbh, $_SESSION['Entidad']);
        // Asignación de valores desde $_REQUEST con isset y valores por defecto.
        $impuestos->rowid = isset($_REQUEST['rowid']) ? intval($_REQUEST['rowid']) : 0;
        $resultado = $impuestos->eliminar_impuesto();

        echo json_encode($resultado);


    break;



    endswitch;
endif;





