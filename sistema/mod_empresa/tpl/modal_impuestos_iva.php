<?php
  session_start();
  //si no hay usuario autenticado, cerrar conexion
  if (!isset($_SESSION['usuario'])) {
    echo acceso_invalido();
         exit(1);
  }


?>




<!-- Modal -->
<div class="modal fade" id="impuestoModal" tabindex="-1" aria-labelledby="impuestoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="impuestoModalLabel">Crea nuevo impuesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form role="form" method="POST" action="" id="formulario_impuestos">
                    <input type="hidden" name="rowid" id="rowid">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="impuesto_texto"><i class="fa fa-fw fa-asterisk"></i> Tipo de IVA</label>
                                <input required="required" type="text" name="impuesto_texto" id="impuesto_texto" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="impuesto"><i class="fa fa-fw fa-asterisk"></i> % IVA</label>
                                <input required="required" type="number" step="0.01" name="impuesto" id="impuesto" class="form-control">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="recargo_equivalencia"><i class="fa fa-fw fa-asterisk"></i> Recargo Equivalencias</label>
                                <input required="required" type="number" step="0.01" name="recargo_equivalencia" id="recargo_equivalencia" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardar_impuesto"><i class="fa fa-fw fa-circle" aria-hidden="true"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>




