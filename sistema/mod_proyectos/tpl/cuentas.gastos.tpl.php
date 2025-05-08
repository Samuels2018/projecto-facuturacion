
<link rel="stylesheet" href="https://www.orangehilldev.com/jstree-bootstrap-theme/demo/assets/dist/themes/proton/style.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/themes/default/style.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.0.9/jstree.min.js"></script>
 <!-- JSTREE -->


 <div class="middle-content container-fluid p-0">

<!-- BREADCRUMB -->
<div class="page-meta">
    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Gastos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cat&aacute;logo de Gastos</li>
        </ol>
    </nav>
</div>
<!-- /BREADCRUMB -->


<!-- CONTENT AREA -->
<div class="row layout-top-spacing">
     <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            


<div class="row">
    <div class="col-xs-6">
        <div class="dataTables_filter" id="example1_filter">
            <label>Buscar Cuenta de Gasto: 
                <div class="ui-widget">
                    <input id="birds" class="form-control" aria-controls="example1">
                </div>
            </label>
            <!-- Botones añadidos aquí con iconos -->
            <div class="btn-group" style="margin-left: 10px;">
              <button id="btnCrear" class="btn btn-primary" onclick="crearCuenta()" disabled>
                  <i class="fas fa-plus"></i> Crear
              </button>
              <button id="btnEditar" class="btn btn-warning" onclick="editarCuenta()" disabled>
                  <i class="fas fa-edit"></i> Editar
              </button>
              <button id="btnEliminar" class="btn btn-danger" onclick="eliminarCuenta()" disabled>
                  <i class="fas fa-trash"></i> Eliminar
              </button>
          </div>
        </div>
    </div>
</div>



 <div class="row mt-5" >
                     <div clas="col-md-6" >
                        <div  id="treeTypesExpenses" style="width:50%" > </div>
                    </div>           

                    
        </div>

              
        </div>
                      
        </div>
                      
        </div>
        </div>

<!-- MODAL  -->
<div class="modal fade" id="cuenta_gasto" tabindex="-1" role="dialog" aria-labelledby="cuenta_gasto_label" aria-hidden="true">
  
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <input type="hidden" id="id_nombre_tipo_gasto" >

        <div class="modal-header">
            <h5 class="modal-title" id="cuenta_gasto_titulo"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
        </div>
        <div class="modal-body">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="ref"><i class="fa fa-fw fa-check"></i> Nombre </label>
                            <input required="required" placeholder="Nombre Tipo de Gasto" type="text" name="forma_label" id="nombre_tipo_gasto" class="form-control" value=""  >
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="ref"><i class="fa fa-fw fa-asterisk"></i> Ajustar Debajo de </label>
                            <Select  placeholder="Nombre forma de pago" type="text" name="selector_html" id="selector_html" class="form-control"  ></select>
                        </div>
                    </div>
                      <div class="row mt-4">
                          <div class="col-md-6">
                              <div class="switch form-switch-custom switch-inline form-switch-primary">
                                  <input class="switch-input" type="checkbox" role="switch" id="activo_tipo_gasto" name="activo_tipo_gasto"  >
                                  <label class="switch-label" for="tosell">Activo</label>
                              </div>
                          </div>

                      </div>
                 </div>
        </div>
        <div class="modal-footer">
            <span id="cuenta_gasto_span"></span>
            <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
            <button type="button" class="btn btn-primary"  OnClick="guardar()" ><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
        </div>

    </div>
</div>


</div>
<!-- MODAL  -->





        
<script>
 // FUNCTION KEYUP EVENT TEXT SEARCH
  $("#birds").on('keyup', function(){
      var to = false;
      if(to) { clearTimeout(to); }
          to = setTimeout(function () {
              var v = $.trim($('#birds').val());
              $('#treeTypesExpenses').jstree(true).search(v);
          }, 250);
  });

  // FUNCTION THAT CHANGES THE ICON OPENING THE NODE
  $('#treeTypesExpenses').on('open_node.jstree', function(e, data){
      var icon = $('#' + data.node.id).find('i.jstree-icon.jstree-themeicon').first();
      icon.removeClass('fa fa-folder').addClass('fa fa-folder-open');
  });

  // FUNCTION THAT CHANGES THE ICON IN CLOSURE OF THE NODE
  $('#treeTypesExpenses').on('close_node.jstree', function(e, data){
      var icon = $('#' + data.node.id).find('i.jstree-icon.jstree-themeicon').first();
      icon.removeClass('fa fa-folder-open').addClass('fa fa-folder');
  });

  // FUNCTION OF THE CLICK EVENT, OF EXPENSES TYPE TREE
  $('#treeTypesExpenses').on('dblclick', '.jstree-anchor', function () {
    alert($(this).parent().attr('id'));
  });
</script>

<script type="text/javascript">
 // OPTION READY WINDOW
let selectedNodeId = null; // Variable para almacenar el ID del nodo seleccionado

$(document).ready(function() {
    // FUNCION LOAD INFO TYPES EXPENSES
    treeInfoLoadTypesExpenses();

    // Evento para manejar la selección de un nodo
    $('#treeTypesExpenses').on("select_node.jstree", function (e, data) {
        selectedNodeId = data.node.id; // Almacena el ID del nodo seleccionado
        toggleButtons(true); // Habilita los botones
    });

    // Evento para manejar la deselección de un nodo
    $('#treeTypesExpenses').on("deselect_node.jstree", function (e, data) {
        selectedNodeId = null; // Resetea el ID del nodo seleccionado
        toggleButtons(false); // Deshabilita los botones
    });
});

// FUNCION PARA HABILITAR O DESHABILITAR BOTONES
function toggleButtons(enable) {
    $('#btnCrear').prop('disabled', !enable);
    $('#btnEditar').prop('disabled', !enable);
    $('#btnEliminar').prop('disabled', !enable);
}

// FUNCION LOAD INFO TYPES EXPENSES
function treeInfoLoadTypesExpenses() {
    $('#treeTypesExpenses').empty();
    $('#treeTypesExpenses').jstree("destroy");

    // EXECUTE POST
    $.ajax({
        url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/json/cuentas.gastos.json.php",
        type: 'POST',
        dataType: 'json',
        accepts: "application/json; charset=utf-8",
        data: {'action': 'treeInfoLoadTypesExpenses'},
        success: function(data) {
            console.log(data);
            $('#treeTypesExpenses').jstree({
                "core": {
                    "animation": 0,
                    "check_callback": false,
                    "themes": { "stripes": true, 'responsive': true },
                    'multiple': false,
                    'data': data
                },
                "types": {
                    "#" : {
                        "max_children": 1,
                        "max_depth": 4,
                        "valid_children": ["root"]
                    },
                    "root" : {
                        "icon": "/static/3.3.8/assets/images/tree_icon.png",
                        "valid_children": ["default"]
                    },
                    "default" : {
                        "valid_children": ["default", "file"]
                    },
                    "file" : {
                        "icon": "glyphicon glyphicon-file",
                        "valid_children": []
                    }
                },
                "contextmenu": {
                    "items": function ($node) {
                        var tree = $("#treeTypesExpenses").jstree(true);
                        return {
                            "Create": {
                                "label": "Crear",
                                "action": function () {
                                    addTypesExpenses($node['id']);
                                }
                            },
                            "Rename": {
                                "label": "Editar",
                                "action": function () {
                                    editTypesExpenses($node['id'], $node['text']);
                                }
                            },
                            "Remove": {
                                "label": "Eliminar",
                                "action": function () {
                                    deleteTypesExpenses($node['id'], $node['text']);
                                }
                            }
                        };
                    }
                },
                "plugins": [
                    "search", "state", "types", "wholerow", "contextmenu"
                ]
            });
        },
        error: function(error) {
            console.info('ajax error', error);
        },
        complete: function(data) {
            $('#treeTypesExpenses').jstree("deselect_all");
            $("#treeTypesExpenses").jstree("close_all");
        }
    });
}

// Funciones para los botones
function crearCuenta() {
    // Busca el nodo que está clickeado en JSTree
    const clickedNode = $('.jstree-anchor.jstree-clicked');

    if (clickedNode.length > 0) {
        // Obtiene el ID del nodo clickeado
        const fullId = clickedNode.attr('id'); // Obtiene el atributo ID completo, por ejemplo "971_anchor"
        const selectedNodeId = fullId.split('_')[0]; // Separa el ID y toma solo el número

        // Aquí puedes llamar a tu función para agregar tipos de gastos
        addTypesExpenses(selectedNodeId);
    } else {
        alert("Seleccione un nodo para crear una subcuenta.");
    }
}

function editarCuenta() {
    // Busca el nodo que está clickeado en JSTree
    const clickedNode = $('.jstree-anchor.jstree-clicked');

    if (clickedNode.length > 0) {
        // Obtiene el ID del nodo clickeado
        const fullId = clickedNode.attr('id'); // Obtiene el atributo ID completo
        const selectedNodeId = fullId.split('_')[0]; // Toma solo el número

        const node = $('#treeTypesExpenses').jstree(true).get_node(selectedNodeId);
        editTypesExpenses(selectedNodeId, node.text);
    } else {
        alert("Seleccione un nodo para editar.");
    }
}

function eliminarCuenta() {
    // Busca el nodo que está clickeado en JSTree
    const clickedNode = $('.jstree-anchor.jstree-clicked');

    if (clickedNode.length > 0) {
        // Obtiene el ID del nodo clickeado
        const fullId = clickedNode.attr('id'); // Obtiene el atributo ID completo
        const selectedNodeId = fullId.split('_')[0]; // Toma solo el número

        // Validar si el ID es 0 o nulo, y mostrar un mensaje
        if (selectedNodeId === '0' || selectedNodeId === null) {
            add_notification({
                text: 'No puedes eliminar la carpeta principal.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return; // Salir de la función si se intenta eliminar la carpeta principal
        }

        const node = $('#treeTypesExpenses').jstree(true).get_node(selectedNodeId);

        // Verificar si el nodo tiene hijos
        if (node.children.length > 0) {
            add_notification({
                text: 'No puedes borrar esta categoría porque contiene subcategorías.',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
            return; // Salir de la función si el nodo tiene hijos
        }

        deleteTypesExpenses(selectedNodeId, node.text);
    } else {
        add_notification({
                text: 'Selecciona un nodo a eliminar',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
    }
}
  // FUNCION ADD INFO TYPE EXPESES
  function addTypesExpenses(parent){
 
    $.ajax({
                  method: "POST",
                  url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
                  beforeSend: function(xhr) {        },
                  data: 
                  {  
                      action    : 'obtener_cuentas' , 
                      fk_parent : parent 
                  }  ,
            }).done(function(result) {
                    console.log(result);
                    var dato = JSON.parse(result);

                    $("#id_nombre_tipo_gasto" ).val( );
                    $("#cuenta_gasto_titulo"  ).empty().html();
                    $("#nombre_tipo_gasto"    ).val("");
                    $("#selector_html"        ).empty().html(dato.selector_html);
                    $("#cuenta_gasto_span"    ).empty().empty( );
                    $("#activo_tipo_gasto").prop("checked", true);
                    $("#cuenta_gasto").modal('show');  


          }).fail(function(jqXHR, textStatus, errorThrown) {
                          console.error("Error en la solicitud: ", textStatus, errorThrown);
                // Manejar el error, como mostrar un mensaje al usuario
          });



  }


   // FUNCION EDIT INFO TYPE EXPESES
  function editTypesExpenses(rowid, name){
 
            $.ajax({
                  method: "POST",
                  url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
                  beforeSend: function(xhr) {        },
                  data: 
                  {  
                      action  : 'obtener_detalle',
                      id      : rowid
                  }  ,
            }).done(function(result) {
                    console.log(result);
                    var dato = JSON.parse(result);

                    $("#id_nombre_tipo_gasto" ).val( dato.id);
                    $("#cuenta_gasto_titulo"  ).empty().html(dato.nombre);
                    $("#nombre_tipo_gasto"    ).val(dato.nombre);
                    $("#selector_html"        ).empty().html(dato.selector_html);
                    $("#cuenta_gasto_span"    ).empty().html(`La cuenta de Gastos <strong>${dato.nombre}</strong> ha recibido   ${dato.documentos_afectados} documentos ` );

                    if (dato.activo == 1) {
                            $("#activo_tipo_gasto").prop("checked", true);
                    } else {
                            $("#activo_tipo_gasto").prop("checked", false);
                    }


                    $("#cuenta_gasto").modal('show');  


          }).fail(function(jqXHR, textStatus, errorThrown) {
                          console.error("Error en la solicitud: ", textStatus, errorThrown);
                // Manejar el error, como mostrar un mensaje al usuario
          });

    }



    function guardar( ){

      nombre_tipo_gasto  =  $("#nombre_tipo_gasto"    ).val( );
      id                 =  $("#id_nombre_tipo_gasto" ).val( );
      parent             =  $("#selector_html"        ).val( );

      if (nombre_tipo_gasto ==  ""  || nombre_tipo_gasto ==undefined ){ return false; }
    
    // POST
    $.ajax({
      method: "POST",
      url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
      beforeSend: function(xhr) { },
      data: 
      {  
          action  : 'editTypesExpenses' ,
          name    : nombre_tipo_gasto   ,
          parent  : parent              ,
          id      : id
      }  ,
    }).done(function(result) {
      var dato = JSON.parse(result);

      if (dato.exito){
          $("#cuenta_gasto").modal('hide');  
          $("#nombre_tipo_gasto").val("");
      }

        treeInfoLoadTypesExpenses();
     
    });



  }

  // FUNCION DELETE INFO TYPE EXPESES
  function deleteTypesExpenses(rowid, name) {
    // VALID CONFIRMATION
    if (confirm("Está seguro(a) que desea eliminar el tipo de gasto: " + name)) {
        // POST
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_gastos_europa/class/gastos.class.php",
            beforeSend: function(xhr) {},
            data: {
                action: 'eliminar_tipo_gasto',
                name: name,
                parent: rowid
            },
        }).done(function(result) {
            try {
                const data = JSON.parse(result); // Asegurarse de parsear el JSON
                // VALIDAR RESULTADO
                if (data.exito === 0) {
                    // Error al eliminar
                    add_notification({
                        text: data.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                    });
                } else if (data.exito === 1) {
                    // Eliminado con éxito
                    add_notification({
                        text: data.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#4caf50',
                    });
                    // FUNCION LOAD INFO TYPES EXPENSES
                    treeInfoLoadTypesExpenses();
                }
            } catch (error) {
                console.error("Error procesando la respuesta del servidor:", error);
                add_notification({
                    text: "Error procesando la respuesta del servidor.",
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
            add_notification({
                text: "Error al comunicarse con el servidor.",
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',
            });
        });
    }
}

</script>


