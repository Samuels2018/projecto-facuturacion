<?php 

include_once(ENLACE_SERVIDOR . "mod_documentos_mercantiles/tpl/header_document.php");
include_once(ENLACE_SERVIDOR . "mod_campos_extra_formularios/object/campos.extra.object.php");


switch ($_GET['tabla']) {
	case 'fi_europa_albaranes_compras':
		break;
	case 'fi_europa_compras':
		break;
	case 'fi_europa_presupuestos':
		break;

    case 'fi_europa_facturas':
        include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/object/facturas.object.php");
        $Documento = new Factura($dbh, $_SESSION['Entidad']);
    break;

       case 'fi_europa_albaranes_ventas':
		break;
	case 'fi_europa_pedidos':
		break;
	
    default:
		echo acceso_invalido("Tabla No configurada aun");
        exit(1);
		break;
}




$id = $_GET['fiche'];
$tipo = $Documento->nombre_clase;
$Documento->fetch($id);



$Extra = new Extra($dbh, $_SESSION['Entidad']);

?>


<div class="middle-content container-xxl p-0">
   <div class="page-meta mb-4">
      <nav class="breadcrumb-style-one" aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo ENLACE_WEB; ?>">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo ENLACE_WEB; ?>factura_listado"><?php echo $Documento->documento_txt['singular']; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $Documento->referencia; ?></li>
         </ol>
      </nav>
   </div>
<?php
if  (empty($Documento->id)){ 
    echo acceso_invalido("Documento Fuera del Acceso Permitido");
    exit(1);
}

$formulario = $Extra->Generar_Formulario($Documento->documento, $Documento->id);
 
 


?>

<form id="miFormulario">   

<div class="content">
    <div class="row">
         <div class="col-md-7">
            <div class="card">
               <div class="card-body">
                            <div class="row">
                                    <?php echo $formulario; ?>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<div class="row mt-5">
            <div class="col-xs-12">

            <a href="<?php echo ENLACE_WEB; ?><?php echo $Documento->ver_url; ?>/<?php echo  $Documento->id; ?>" class="btn btn-outline-primary _effect--ripple waves-effect waves-light">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                  Volver a <?php echo $Documento->documento_txt['singular']; ?>
               </a>


               <a href="#" class="btn btn-info _effect--ripple waves-effect waves-light" OnClick="guardar_datos();">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                  Actualizar 
               </a>

            </div>
          </div>



<script>

    function guardar_datos( ){


    
            let datosForm = $('#miFormulario').serializeArray();

            datosForm.push({ name: 'documento'  , value: '<?php echo $Documento->id; ?>' });
            datosForm.push({ name: 'accion'     , value: 'valor2' });


            console.log(datosForm);

            let formulario = $('#miFormulario')[0];
                let valido = true;

                // Limpiar errores previos
                $('#miFormulario .input_error').removeClass('input_error');

           // Validar inputs requeridos
            $('#miFormulario [required]').each(function () {
                const valor = this.value.trim();
                const tipo = $(this).attr('type');

                if (!valor) {
                    $(this).addClass('input_error');
                    valido = false;
                    return;
                }

                // Validar emails
                if (tipo === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(valor)) {
                        $(this).addClass('input_error');
                        valido = false;
                    }
                }
            });

            // Si no es válido, salir
            if (!valido) {
                add_notification({
                    text: 'Por favor completa correctamente todos los campos requeridos.',
                    actionTextColor: '#fff',
                    backgroundColor: '#e53935',
                    dismissText: 'Cerrar'
                });
                return; // Evita el envío
            }



 

            // Enviar por AJAX
            $.ajax({
                url: '<?php echo ENLACE_WEB; ?>mod_campos_extra_formularios/json/extras.json.php', // Cambia esto a tu URL
                type: 'POST',
                data: $.param(datosForm),
                success: function(respuesta) {
                     console.log('Éxito:', respuesta);

                    var data = $.parseJSON(respuesta);

                                add_notification({
                                    text: data.mensaje,
                                    actionTextColor: '#fff',
                                    backgroundColor: '#00ab55',
                                    dismissText: 'Cerrar'
                                });



                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });

} //guardar_datos

</script>