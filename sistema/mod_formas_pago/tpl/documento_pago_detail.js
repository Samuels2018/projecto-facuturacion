$(document).ready(function () {
     let counter = 1;

     // Datos iniciales 
     // const initialData = [
     //      { "forma_id": 1, "forma_pago": 10, "forma_importe": 150.50, "forma_dias": 15, "forma_vencimiento": "2024-10-20" },
     //      { "forma_id": 2, "forma_pago": 10, "forma_importe": 150.50, "forma_dias": 15, "forma_vencimiento": "2024-10-20" }
     // ];
     const sourceInput = $('#forma_detalle_json').val();

     const initialData = (sourceInput!='' && sourceInput!='null')? JSON.parse(sourceInput).map(item => ({ "forma_id": parseInt(item.secuencia), "forma_porcentaje": parseFloat(item.porcentaje), "forma_importe": parseFloat(item.importe), "forma_dias": parseInt(item.dias), "forma_vencimiento": item.vencimiento.split(" ")[0]})):null

     // Inicializar DataTable
     forma_table = $('#forma_detail').DataTable({
          // data: ( JSON.parse($('#forma_detalle_json').val()) || [] ),
          data: initialData,
          searching: false,
          // bInfo : false,
          bLengthChange: false,
          bFilter: false,
          oLanguage: estiloPaginado.oLanguage,
          dom: estiloPaginado.dom,
          scroller: true,
          bPaginate: true,
          bAutoWidth: false,
          bSort: false,
          retrieve: true,
          deferRender: true,
          scroller: true,
          responsive: true,
          pageLength: 5,
          aoColumns: [
               { data: 'forma_id', sWidth: '2%', },
               {
                    data: 'forma_porcentaje', sWidth: '30%', render: function (data, type, row) {
                         return `<input type="number" step="0.01" class="form-control forma_porcentaje" style="padding:0" value="${data}">`;
                    }
               },
               {
                    data: 'forma_importe', sWidth: '30%', render: function (data, type, row) {
                         return `<input type="number" step="0.01" class="form-control forma_importe" style="padding:0" value="${data.toFixed(2)}">`;
                    }
               },
               {
                    data: 'forma_dias', sWidth: '5%', render: function (data, type, row) {
                         return `<input type="number" step="1" class="form-control forma_dias text-center" style="padding:0" value="${data}">`;
                    }
               },
               {
                    data: 'forma_vencimiento', sWidth: '30%', render: function (data, type, row) {
                         return `<input type="date" class="form-control forma_vencimiento" value="${data}">`;
                    }
               },
               { 
                    data: null, 
                    defaultContent: '' 
               }
               // {
               //      data: null,
               //      render: function (data, type, row, meta) {
               //           $('.delete-row').remove()
               //           if(forma_table != null) {
               //                debugger
               //                let count_rows = forma_table.rows().count();
               //                if (meta.row == count_rows - 1) {
               //                     return '<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>'
               //                } else {
               //                     return ''
               //                }
               //           } else {
               //                return ''
               //           }
               //      },
               //      // defaultContent: '<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>' 
               // }
          ],
          drawCallback: function(settings) { 
               // Remover todos los botones de eliminación primero 
               $('.delete-row').remove(); 
               // Agregar botón de eliminación en la última fila
               let count_rows = settings.fnRecordsTotal();
               if(count_rows > 0) { 
                    $($('#forma_detail tbody tr')[count_rows-1]).find('td:last').html('<button type="button" class="btn btn-danger delete-row"><i class="fa fa-trash"></i></button>'); 
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
               forma_id: count_rows+1,
               forma_porcentaje: 0.00,
               forma_importe: 0.00,
               forma_dias: 0,
               forma_vencimiento: ''
          }).draw(false);
          calcularFormasIguales()
          calcularUltimo()
     });

     // Editar fila
     $('#forma_detail tbody').on('click', '.edit-row', function () {
          const row = $(this).closest('tr');
          const data = forma_table.row(row).data();

          data.forma_porcentaje = row.find('input.forma_porcentaje').val();
          data.forma_importe = parseFloat(row.find('input.forma_importe').val()).toFixed(2);
          data.forma_dias = row.find('input.forma_dias').val();
          data.forma_vencimiento = row.find('input.forma_vencimiento').val();

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

     $('#forma_iguales').on('click', function () {
          calcularFormasIguales()
     })
     $('#forma_ultimo').on('click', function () {
          calcularUltimo()
     })

     function calcularFormasIguales() {
          let forma_iguales_checked = $('#forma_iguales').prop('checked')
          let count_rows = forma_table.rows().count();
          if (count_rows == 0) return;
          if (forma_iguales_checked) {
               let mount_rows = parseFloat(forma_documento_monto / count_rows)
               let mount_rows_counter = 0
               for (i = 0; i < count_rows - 1; i++) {
                    mount_rows_counter += parseFloat(mount_rows)
                    $('.forma_porcentaje')[i].value = mount_rows
                    $('.forma_importe')[i].value = mount_rows.toFixed(2)
               }
               $('.forma_porcentaje').val(parseFloat(mount_rows / 100).toFixed(2))
               $('.forma_importe')[count_rows - 1].value = (forma_documento_monto - mount_rows_counter).toFixed(2)
          }


          $('.forma_porcentaje').prop('disabled', (forma_iguales_checked ? 'disabled' : ''))
          $('.forma_importe').prop('disabled', (forma_iguales_checked ? 'disabled' : ''))
     }
     function calcularUltimo() {
          let forma_ultimo_checked = $('#forma_ultimo').prop('checked')
          let count_rows = forma_table.rows().count();
          if (count_rows == 0) return;
          if (forma_ultimo_checked) {
               let forma_fecha_base = obtenerFechaActual()
     
               $('.forma_dias')[0].value = parseInt(diasRestantesEnMes(forma_fecha_base))
               $('.forma_vencimiento')[0].value = formatearFecha(obtenerUltimoDiaDelMes(forma_fecha_base), 'aaaa-mm-dd')
     
               for (i = 1; i < count_rows; i++) {
                    let nueva_fecha = modificarDias(new Date($('.forma_vencimiento')[0].value), 30 * i)
                    $('.forma_dias')[i].value = 30
                    $('.forma_vencimiento')[i].value = formatearFecha(nueva_fecha, 'aaaa-mm-dd')
               }
          }

          $('.forma_dias').prop('disabled', (forma_ultimo_checked ? 'disabled' : ''))
          $('.forma_vencimiento').prop('disabled', (forma_ultimo_checked ? 'disabled' : ''))
     }
});

function guardar_modal_forma_pago(event){
     let tableData = [];
     $('#forma_detail tbody tr').each(function() {
         let row = {
          forma_id: $(this).find('td').eq(0).text().trim(),
          forma_secuencia: $(this).find('td').eq(0).text().trim(),
          forma_porcentaje: $(this).find('.forma_porcentaje').val(),
          forma_importe: $(this).find('.forma_importe').val(),
          forma_dias: $(this).find('.forma_dias').val(),
          forma_vencimiento: $(this).find('.forma_vencimiento').val()
         };
         tableData.push(row);
     });
     forma_table.data_json = tableData;
     crear_forma_pago(event)
}

function guardar_modal_actualiza_formapago(id){
     let tableData = [];
     $('#forma_detail tbody tr').each(function() {
         let row = {
          forma_id: $(this).find('td').eq(0).text().trim(),
          forma_secuencia: $(this).find('td').eq(0).text().trim(),
          forma_porcentaje: $(this).find('.forma_porcentaje').val(),
          forma_importe: $(this).find('.forma_importe').val(),
          forma_dias: $(this).find('.forma_dias').val(),
          forma_vencimiento: $(this).find('.forma_vencimiento').val()
         };
         tableData.push(row);
     });
     forma_table.data_json = tableData;
     actualizar_forma_pago(id)
}