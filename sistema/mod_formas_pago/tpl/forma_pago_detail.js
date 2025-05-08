$(document).ready(function () {
     const sourceInput = $('#forma_detalle_json').val();

     const initialData = (sourceInput != '' && sourceInput != 'null') ? JSON.parse(sourceInput).map(item => ({ "forma_id": parseInt(item.secuencia), "forma_porcentaje": parseFloat(item.porcentaje), "forma_dias": parseInt(item.dias) })) : null

     // Inicializar DataTable
     forma_table = $('#forma_detail').DataTable({
          data: initialData,
          searching: false,
          bInfo : false,
          bLengthChange: false,
          bFilter: false,
          oLanguage: estiloPaginado.oLanguage,
          dom: estiloPaginado.dom,
          scroller: true,
          scrollCollapse: true,
          scrollY: '40vh',
          bPaginate: false,
          bAutoWidth: false,
          bSort: false,
          retrieve: true,
          deferRender: true,
          responsive: true,
          // pageLength: 5,
          aoColumns: [
               { data: 'forma_id', sWidth: '2%', },
               {
                    data: 'forma_porcentaje', sWidth: '30%', render: function (data, type, row) {
                         return `<input type="number" step="0.01" min="1" max="100" class="form-control forma_porcentaje text-center" style="padding:0" value="${data}">`;
                    }
               },
               {
                    data: 'forma_dias', sWidth: '30%', render: function (data, type, row) {
                         return `<input type="number" step="1" class="form-control forma_dias text-center" style="padding:0" value="${data}">`;
                    }
               },
               {
                    data: null, sWidth: '5%',
                    defaultContent: ''
               }
          ],
          drawCallback: function (settings) {
               // Remover todos los botones de eliminación primero 
               $('.delete-row').remove();
               // Agregar botón de eliminación en la última fila
               let count_rows = settings.fnRecordsTotal();
               if (count_rows > 0) {
                    $($('#forma_detail tbody tr')[count_rows - 1]).find('td:last').html('<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>');
               }
          },
          initComplete: function () {
               $('.dt--top-section').remove()
          }
     });

     // Agregar nueva fila
     $('#forma_addRow').on('click', function () {
          let count_rows = forma_table.rows().count();
          forma_table.row.add({
               forma_id: count_rows + 1,
               forma_porcentaje: 0.00,
               forma_dias: 0,
          }).draw(false);
          calcularFormasIguales()
          calcularUltimo()
     });

     // Editar fila
     $('#forma_detail tbody').on('click', '.edit-row', function () {
          const row = $(this).closest('tr');
          const data = forma_table.row(row).data();

          data.forma_porcentaje = row.find('input.forma_porcentaje').val();
          data.forma_dias = row.find('input.forma_dias').val();

          forma_table.row(row).data(data).invalidate();
     });

     // Eliminar fila 
     $('#forma_detail tbody').on('click', '.delete-row', function () {
          let count_rows = forma_table.rows().count();
          const row = $(this).closest('tr');
          $($('#forma_detail').find('tr')[count_rows - 1]).find('td:last').html(
               '<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash" aria-hidden="true"></i></button>'
          )

          forma_table.row(row).remove().draw();
          calcularFormasIguales()
          calcularUltimo()
     });

     $('#forma_importes_iguales').on('click', function () {
          calcularFormasIguales()
     })
     $('#forma_ultimo_dia').on('click', function () {
          calcularUltimo()
     })

     function calcularFormasIguales() {
          let forma_iguales_checked = $('#forma_importes_iguales').prop('checked')
          let count_rows = forma_table.rows().count();
          if (count_rows == 0) return;
          if (forma_iguales_checked) {
               // let mount_rows = parseFloat(forma_documento_monto / count_rows)
               let mount_rows = parseFloat(100 / count_rows)
               let mount_rows_counter = 0
               for (i = 0; i < count_rows ; i++) {
                    if(i == count_rows-1){
                         if(parseFloat(100-mount_rows_counter) != 100){
                              $('.forma_porcentaje')[i].value = parseFloat(100-mount_rows_counter).toFixed(2)
                         }else{
                              mount_rows_counter += parseFloat(parseFloat(mount_rows).toFixed(2))
                              $('.forma_porcentaje')[i].value = parseFloat(mount_rows).toFixed(2)
                         }
                    }else{
                         mount_rows_counter += parseFloat(parseFloat(mount_rows).toFixed(2))
                         $('.forma_porcentaje')[i].value = parseFloat(mount_rows).toFixed(2)
                    }
               }
               // $('.forma_porcentaje').val(parseFloat(100-mount_rows_counter).toFixed(2))               
               // $('.forma_porcentaje').val(parseFloat(mount_rows).toFixed(2))
          }
          $('.forma_porcentaje').prop('disabled', (forma_iguales_checked ? 'disabled' : ''))
     }
     function calcularUltimo() {
          let forma_ultimo_checked = $('#forma_ultimo_dia').prop('checked')
          let count_rows = forma_table.rows().count();
          if (count_rows == 0) return;
          if (forma_ultimo_checked) {
               // let forma_fecha_base = obtenerFechaActual()
               // $('.forma_dias')[0].value = parseInt(obtenerDiasEnMes(forma_fecha_base.getFullYear(), forma_fecha_base.getMonth()))
               // for (i = 1; i < count_rows; i++) {
               //      forma_fecha_base = modificarMeses(forma_fecha_base, 1)
               //      $('.forma_dias')[i].value = parseInt(obtenerDiasEnMes(forma_fecha_base.getFullYear(), forma_fecha_base.getMonth()))
               // }

               for (i = 0; i < count_rows; i++) {
                    $('.forma_dias')[i].value = 30
               }
          }

          $('.forma_dias').prop('disabled', (forma_ultimo_checked ? 'disabled' : ''))
     }

     calcularFormasIguales()
     calcularUltimo()
});

function guardar_modal_forma_pago(event) {
     let tableData = [];
     let hasErrors = false;
     $('#forma_detail tbody tr').each(function () {
          let row = {
               forma_id: $(this).find('td').eq(0).text().trim(),
               forma_secuencia: $(this).find('td').eq(0).text().trim(),
               forma_porcentaje: $(this).find('.forma_porcentaje').val(),
               forma_dias: $(this).find('.forma_dias').val()
          };
          tableData.push(row);
     });
     if(validaConteoDetalle()){ return; }
     forma_table.data_json = tableData;
     crear_forma_pago(event)
}

function guardar_modal_actualiza_formapago(id) {
     let tableData = [];
     $('#forma_detail tbody tr').each(function () {
          let row = {
               forma_id: $(this).find('td').eq(0).text().trim(),
               forma_secuencia: $(this).find('td').eq(0).text().trim(),
               forma_porcentaje: $(this).find('.forma_porcentaje').val(),
               forma_dias: $(this).find('.forma_dias').val()
          };
          tableData.push(row);
     });
     if(validaConteoDetalle()){ return; }
     forma_table.data_json = tableData;
     actualizar_forma_pago(id)
}

function validaConteoDetalle(){
     let hasErrors = false;
     let count_pago = 0;
     $('#forma_detail tbody tr').each(function () {
          if ($(this).find('.forma_porcentaje').val() == '') {
               add_notification({
                    text: "Warning:" + 'Debe completar todos los pagos',
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
               });
               hasErrors = true;
          }
          if ($(this).find('.forma_dias').val() == '') {
               add_notification({
                    text: "Warning:" + 'Debe completar todos los pagos',
                    actionTextColor: '#fff',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a',
               });
               hasErrors = true;
          }
          count_pago += parseFloat($(this).find('.forma_porcentaje').val());
     });
     if(count_pago != 100){
          add_notification({
               text: "Warning:" + '% Pagos debe sumar 100%',
               actionTextColor: '#fff',
               actionTextColor: '#fff',
               backgroundColor: '#e7515a',
          });
          hasErrors = true;
     }

     return hasErrors
}