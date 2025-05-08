<style id="style_documents">
     #style-3_filter {
          display: none;
     }

     #style-3_length {
          display: flex;
     }

     #export-buttons-container {
          margin-left: 25px;
     }

     #export-buttons-container button+button {
          margin-left: 15px;
     }

     #columnVisibilityContainer {
          margin-top: 40px !important;
     }

     /* Estilos para las tablas de detalle y totales de un documento */
     .tooltip_item {
          position: relative;
          display: inline-block;
          border-bottom: 1px dotted black;
     }

     .tooltip_item .tooltiptext {
          visibility: hidden;
          background-color: black;
          color: #fff;
          text-align: center;
          border-radius: 6px;
          padding: 5px 0;
          position: absolute;
          z-index: 1;
     }
     .tooltip_item:hover .tooltiptext {
          visibility: visible;
     }

     .tabla_sin_borde {
          border-collapse: collapse;
          border: none;
     }
     .borroso {
          filter: blur(3px);
     }

     .styled-row {
          background-color: #e0ffe0 !important;
          /* Fondo verde muy bajito */
          position: relative;
          /* Necesario para el pseudo-elemento */
     }

     .styled-row::after {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          border: 2px solid green;
          /* Border verde */
          pointer-events: none;
          /* Permite la interacción con el contenido de la fila */
     }

     #tabla_facturacion th {
          text-align: center;
          /* Centra los títulos de la cabecera */
          color: gray;
          border-color: gray;
     }

     #tabla_facturacion td,
     #tabla_facturacion th {
          border-color: gray;
     }

     #tabla_facturacion td.text-right {
          text-align: right;
          /* Alínea los números a la derecha */
     }

     #tabla_facturacion td.text-left {
          text-align: left;
          /* Alínea los textos a la izquierda */
     }

     #tabla_facturacion-striped tbody tr:nth-of-type(odd) {
          background-color: rgba(0, 0, 0, .05);
     }
     /* Estilos para las tablas de detalle y totales de un documento */

     /* Estilo de oculto para la linea edit de la grilla del documento */
     .oculto_ {
        display: none;
    }

 

</style>

<style>
   .ui-autocomplete {
      list-style-type: none;
      padding: 0;
      margin: 0;
   }

   .ui-menu-item {
      width: 100% !important;
   }

   .fixed-width {
      width: 100px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
   }

   .tabla_sin_borde {
      border-collapse: collapse;
      border: none;
   }

   .valid {
      padding-right: calc(1.5em + .75rem);
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right calc(.375em + .1875rem) center;
      background-size: calc(.75em + .375rem) calc(.75em + .375rem);
      border-color: #009688;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23009688' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-check'%3e%3cpolyline points='20 6 9 17 4 12'%3e%3c/polyline%3e%3c/svg%3e");
   }

   #textarea_detalle {
      width: 100%;
      height: 100%;
      box-sizing: border-box;
      resize: none;
   }

   .fantasma.form-control {
      background: transparent;
      border: 0px;
   }

   .fantasma.form-control:focus {
      background: #fff;
      border: 1px solid #bfc9d4;
      cursor: text;
   }

   #_tipo_servicio_producto {
      min-width: 90px;
   }
</style>

<!--  BEGIN CUSTOM STYLE FILE  -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/scrollspyNav.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/light/components/timeline.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/scrollspyNav.css" rel="stylesheet" type="text/css" />
<link href="<?php echo ENLACE_WEB; ?>bootstrap/assets/css/dark/components/timeline.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">