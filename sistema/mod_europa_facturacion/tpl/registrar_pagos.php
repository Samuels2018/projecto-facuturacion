<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Pagar Factura <?= $id ?>  </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="paymentForm">
        <div class="modal-body">
          <input id="remaining_hidden_value" type="hidden" value="<?php echo $Documento->total - $Documento->pagado; ?>">

          <!-- Invoice Summary -->
          <div class="mb-3">
            <p><strong>Total Factura:</strong> 
              <span id="total_invoice">
                <?= $Documento->moneda_simbolo . " " . numero_decimal($Documento->total) ?> 
              </span>
            </p>
            <p><strong>Total Pagado:</strong> <span id="total_paid">   <?= $Documento->moneda_simbolo . " " . numero_decimal($Documento->pagado) ?>  </span></p>
            <p><strong>Queda por Pagar en Factura <?= $id ?> :</strong> <span id="remaining_balance" class="text-danger">  <?= $Documento->moneda_simbolo . " " . numero_decimal($Documento->total - $Documento->pagado) ?>  </span></p>
          </div>

          <!-- Payment Amount -->
          <div class="mb-3">
            <label for="payment_amount" class="form-label"><strong>Monto del Pago</strong></label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="0.01" >
              <input type="hidden" class="form-control"  value="<?= $id ?>" id="fk_documento" name="fk_documento" >
            </div>
          </div>

          <!-- Payment Date -->
          <div class="mb-3">
            <label for="payment_date" class="form-label"><strong>Fecha del Pago</strong></label>
            <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?= date('Y-m-d'); ?>" >
          </div>

          <!-- Payment Method -->
          <div class="mb-3">
            <label for="payment_method" class="form-label"><strong>Método de Pago</strong></label>
            <select class="form-select" id="payment_method" name="payment_method" >
              <option value="1" selected>Efectivo</option>
              <option value="2">Tarjeta</option>
            </select>
          </div>

          <!-- Comment -->
          <div class="mb-3">
            <label for="payment_comment" class="form-label"><strong>Comentario</strong></label>
            <textarea class="form-control" id="payment_comment" name="payment_comment" rows="3" placeholder="Comentario"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Realizar El Pago</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include_once(ENLACE_SERVIDOR . "mod_europa_facturacion/tpl/registrar_pagos_scripts.php"); ?>

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