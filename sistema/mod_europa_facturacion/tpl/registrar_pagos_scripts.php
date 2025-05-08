    <script>
        // Event listener for form submission
        document.getElementById('paymentForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            // Collect form datas
            const fk_documento = document.getElementById('fk_documento').value;
            const monto = document.getElementById('payment_amount').value;
            const fecha_pago = document.getElementById('payment_date').value;
            const forma_pago = document.getElementById('payment_method').value;
            const comentario = document.getElementById('payment_comment').value;

            const monto_pendiente = document.getElementById('remaining_hidden_value').value;

            // Validate required fields
            if (!monto || !fecha_pago || !forma_pago) {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos requeridos.',
                });
                return;
            }
            if( parseFloat(monto_pendiente) < parseFloat(monto) ){
                Swal.fire({
                    icon: 'error',
                    title: 'Monto no puede exceder al pendiente por pagar',
                    text: 'Validación de montos.',
                });
                return;
            }

            // Send POST request
            const url = "<?php echo ENLACE_WEB; ?>mod_documentos_mercantiles/json/guardar_pagos.php"

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fk_documento: fk_documento,
                    tipo: 'factura',
                    monto: monto,
                    fecha_pago: fecha_pago,
                    forma_pago: forma_pago,
                    comentario: comentario,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Pago registrado',
                            text: data.message,
                        }).then(() => {
                            // Close modal and reload page
                            const modalElement = document.getElementById('paymentModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            modalInstance.hide();
                            location.reload();
                        });
                    } else {
                        // Show error message from the backend
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al registrar el pago',
                            text: data.message,
                        });
                    }
                })
                .catch((error) => {
                    // Handle network or unexpected errors
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de red',
                        text: 'Hubo un problema al registrar el pago. Inténtelo nuevamente más tarde.',
                    });
                });
        });
    </script>
