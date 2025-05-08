<style>
  .modal-title {
    font-size: 1.5rem;
    color: #007bff;
     }

     .text-danger {
     font-weight: bold;
     }

     .input-group-text {
     background-color: #f8f9fa;
     }
</style>

<?php
$tipos_contactos = $cliente->obtener_tipo_contacto();

?>

<!-- Modal -->
<div class="modal fade" id="contactoModal" tabindex="-1" aria-labelledby="contactoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="contactoModalLabel">Contacto <?= $id ?>  </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="contactoForm">
        <div class="modal-body">
          <input type="hidden" id="contacto_id" value="">
          <div class="mb-3">
            <label for="contacto_dato" class="form-label"><strong>Dato</strong></label>
            <div class="input-group">
              <input type="text" class="form-control" id="contacto_dato" name="contacto_dato" >
            </div>
          </div>
          <div class="mb-3">
            <label for="contacto_detalle" class="form-label"><strong>Detalle</strong></label>
            <div class="input-group">
              <input type="text" class="form-control" id="contacto_detalle" name="contacto_detalle" >
            </div>
          </div>
          <div class="mb-3">
            <label for="contacto_tipo" class="form-label"><strong>Tipo de contacto</strong></label>
            <select class="form-select" id="contacto_tipo" name="contacto_tipo" >
               <?php
               foreach ($tipos_contactos as $key => $value) {
               ?>
               <option value="<?php echo $value->rowid; ?>"><?php echo $value->label; ?></option>
               <?php
               }
               ?>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Grabar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
        // Event listener for form submission
        document.getElementById('contactoForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            // Collect form datas
            const contacto_id = document.getElementById('contacto_id').value;
            const fk_tercero = document.getElementById('fiche').value;
            const contacto_dato = document.getElementById('contacto_dato').value;
            const contacto_detalle = document.getElementById('contacto_detalle').value;
            const contacto_tipo = document.getElementById('contacto_tipo').value;

            // Validate required fields
            if (!contacto_dato || !contacto_detalle || !contacto_tipo) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos requeridos.',
                });
                return;
            }

            $.post("<?php echo ENLACE_WEB; ?>mod_terceros/class/terceros.class.php", {
                contacto_tipo: contacto_tipo,
                contacto_data: contacto_dato,
                contacto_detalle: contacto_detalle,
                rowid: '<?php echo $_REQUEST['fiche']; ?>',
                contacto_id: contacto_id,
                action: (contacto_id!=''?'modificar_dato_contacto': 'agregar_dato_contacto')
            })
            .done(function(data) {
                let response = JSON.parse(data);
                if (response.error == 0) {
                    console.log('entro en error');
                    Swal.fire({
                         icon: 'success',
                         title: 'Dato de contacto actualizado exitosamente!',
                    }).then(() => {
                         // Destruir la instancia actual del DataTable
                         $('#tabla-contactos').DataTable().destroy();
                         // Recargar los datos y reinicializar el DataTable
                         cargar_tabla_dato_contacto();
                         $('#contactoModal').modal('hide')

                         $('#contacto_id').val('')
                         $('#contacto_dato').val('')
                         $('#contacto_detalle').val('')
                         $('#contacto_tipo').val('')
                    });
                } else {
                    Swal.fire({
                         icon: 'error',
                         title: 'Error al actualizar contacto',
                         text: response.datos,
                    });
                }
            });
        });
    </script>
