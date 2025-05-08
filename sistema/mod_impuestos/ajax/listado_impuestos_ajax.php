<?php
/* Lista de Usuarios */
/*----------------------------------------------------*/
session_start();
require_once "../../conf/conf.php";

if (!isset($_SESSION['usuario']))
{
    echo acceso_invalido();
         exit(1);
}



require_once ENLACE_SERVIDOR . 'mod_impuestos/object/impuestos_object.php';
$impuestos = new impuestos($dbh,$_SESSION['Entidad']);
$lista_impuestos = $impuestos->listar_impuestos();


                                                      foreach($lista_impuestos as $impuesto) {
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?php echo $impuesto['impuesto_texto']; ?></td>
                                                            <td class="text-center"><?php echo $impuesto['impuesto']; ?></td>
                                                            <td class="text-center"><?php echo $impuesto['recargo_equivalencia']; ?>%</td>

                                                            <td class="text-center">
                                                                <div class="action-btns">
                                                                    <!-- Botón de editar -->
                                                                    <a href="javascript:void(0);" 
                                                                       class="action-btn btn-edit bs-tooltip me-2" 
                                                                       data-toggle="tooltip" 
                                                                       data-placement="top" 
                                                                       title="Edit"
                                                                       data-rowid="<?php echo $impuesto['rowid']; ?>"
                                                                       data-impuesto_texto="<?php echo $impuesto['impuesto_texto']; ?>"
                                                                       data-impuesto="<?php echo $impuesto['impuesto']; ?>"
                                                                       data-recargo_equivalencia="<?php echo $impuesto['recargo_equivalencia']; ?>"
                                                                       data-pais="<?php echo $impuesto['pais']; ?>"
                                                                       data-autogen="<?php echo $impuesto['autogen']; ?>">
                                                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                                                           <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                       </svg>
                                                                    </a>

                                                                    <!-- Botón de eliminar/desactivar -->
                                                                    <a href="javascript:void(0);" 
                                                                       class="action-btn btn-delete bs-tooltip" 
                                                                       data-toggle="tooltip" 
                                                                       data-placement="top" 
                                                                       title="Delete"
                                                                       data-rowid="<?php echo $impuesto['rowid']; ?>">
                                                                       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                                                           <polyline points="3 6 5 6 21 6"></polyline>
                                                                           <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                           <line x1="10" y1="11" x2="10" y2="17"></line>
                                                                           <line x1="14" y1="11" x2="14" y2="17"></line>
                                                                       </svg>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } 


?>

