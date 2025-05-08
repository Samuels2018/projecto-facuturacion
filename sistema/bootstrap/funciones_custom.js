
      
       function add_notification(options) {
            Snackbar.show(options);
            if(options.dismissText != ''){
                $('.snackbar-container').append('<button class="action" style="color:white;" id="cancelButton">Cancelar</button>'); 
                $('#cancelButton').click(function() { Snackbar.close(); });
            }
        }

        function showSnackbar(message, actionText, actionCallback) {
    // Preparar las opciones para el snackbar
    let options = {
        content: message, // El mensaje del snackbar
        actionText: actionText, // El texto del botón de acción
        actionTextColor: '#ffffff', // Color del texto del botón de acción
        backgroundColor: '#333333', // Color de fondo del snackbar
        width: 'auto', // Ancho del snackbar
        showAction: true, // Mostrar el botón de acción
        pos: 'bottom-left', // Posición del snackbar
        duration: 30000, // Duración del snackbar en milisegundos
    };

    // Si se proporciona un callback para la acción, lo asignamos al botón de acción
    if (actionCallback) {
        options.action = actionCallback;
    }

    // Mostrar el snackbar utilizando la API de Snackbar.js
    Snackbar.show(options);
}

//convertir precio a decimal
function numero_decimal(numero) {
    // Crear un formateador de número con opciones específicas
    const formatter = new Intl.NumberFormat('es-ES', {
        // style: 'currency',
        // currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Formatear el número
    const formatted = formatter.format(numero);

    // Reemplazar el símbolo de moneda con el símbolo deseado
    return "₡ " + formatted;
}

function primeraLetraMayuscula(cadena) {
    return cadena.charAt(0).toUpperCase() + cadena.slice(1);
}

function calcularDescuento(precioOriginal, porcentajeDescuento) {
    // Calcular el valor del descuento
    let descuento = precioOriginal * (porcentajeDescuento / 100);

    // Calcular el precio final después del descuento
    let precioFinal = precioOriginal - descuento;

    // Devolver el precio final
    return precioFinal;
}

function numero(n) {
    // Implementación de la función para formatear números
    return n.toLocaleString('es-ES'); // Ejemplo de formateo
}

function numero_dolar(n) {
    // Intenta convertir n a un número
    let numero = Number(n);

    // Verifica si la conversión fue exitosa
    if (!isNaN(numero)) {
        // Formatea el número como un valor en dólares, con dos decimales y un símbolo de dólar
        return '$' + numero.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    } else {
        // Si n no es un número, devuelve un mensaje de error o un valor predeterminado
        console.error('numero_dolar() espera un número, pero recibió:', n);
        return 'Error: Valor no numérico';
    }
}

function generarSelectTipo(id) {
    let select = document.createElement("select");
    select.required = true;
    select.className = "form-control";
    select.name = id;
    select.id = id;

    // Opción por defecto
    let optionDefault = document.createElement("option");
    optionDefault.disabled = false;
    optionDefault.value = "";
    optionDefault.textContent = "Seleccione...";
    select.appendChild(optionDefault);

    // Opciones adicionales
    let options = [
        {value: "fisica", text: "Físico"},
        {value: "juridica", text: "Jurídico"} 
       
    ];

    options.forEach(function(optionData) {
        let option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        select.appendChild(option);
    });

    return select;
}


function generarSelectTipoCategoria(id) {
    let select = document.createElement("select");
    select.required = true;
    select.className = "form-control";
    select.name = id;
    select.id = id;

    // Opción por defecto
    let optionDefault = document.createElement("option");
    optionDefault.disabled = false;
    optionDefault.value = "";
    optionDefault.textContent = "Seleccione...";
    select.appendChild(optionDefault);

    // Opciones para estatus activo e inactivo
    let options = [
        {value: "clientes", text: "clientes"},
        {value: "productos", text: "productos"}
    ];

    options.forEach(function(optionData) {
        let option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        select.appendChild(option);
    });

    return select;
}



function generarSelectEstatus(id) {
    let select = document.createElement("select");
    select.required = true;
    select.className = "form-control";
    select.name = id;
    select.id = id;

    // Opción por defecto
    let optionDefault = document.createElement("option");
    optionDefault.disabled = false;
    optionDefault.value = "";
    optionDefault.textContent = "Seleccione...";
    select.appendChild(optionDefault);

    // Opciones para estatus activo e inactivo
    let options = [
        {value: "1", text: "Activo"},
        {value: "0", text: "Inactivo"}
    ];

    options.forEach(function(optionData) {
        let option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        select.appendChild(option);
    });

    return select;
}

function generarSelectSiNo(id) {
    let select = document.createElement("select");
    select.required = true;
    select.className = "form-control";
    select.name = id;
    select.id = id;

    // Opción por defecto
    let optionDefault = document.createElement("option");
    optionDefault.disabled = false;
    optionDefault.value = "";
    optionDefault.textContent = "Seleccione...";
    select.appendChild(optionDefault);

    // Opciones para estatus activo e inactivo
    let options = [
        {value: "1", text: "Si"},
        {value: "0", text: "No"}
    ];

    options.forEach(function(optionData) {
        let option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        select.appendChild(option);
    });

    return select;
}

function generarSelectGeneral(id, options=[]) {
    // // Opciones para estatus activo e inactivo
    // let options = [
    //     {value: "1", text: "Activo"},
    //     {value: "0", text: "Inactivo"}
    // ];

    let select = document.createElement("select");
    select.required = true;
    select.className = "form-control";
    select.name = id;
    select.id = id;

    // Opción por defecto
    let optionDefault = document.createElement("option");
    optionDefault.disabled = false;
    optionDefault.value = "";
    optionDefault.textContent = "Seleccione...";
    select.appendChild(optionDefault);

    options.forEach(function(optionData) {
        let option = document.createElement("option");
        option.value = optionData.value;
        option.textContent = optionData.text;
        select.appendChild(option);
    });

    return select;
}

function checkall(clickchk, relChkbox) {

    var checker = $('#' + clickchk);
    var multichk = $('.' + relChkbox);


    checker.click(function () {
        multichk.prop('checked', $(this).prop('checked'));
    });
}


function obtenerFechaActual() {
    return new Date();
}
function modificarDias(fecha, dias) {
    var nuevaFecha = new Date(fecha);
    nuevaFecha.setDate(nuevaFecha.getDate() + dias);
    return nuevaFecha;
}
function modificarMeses(fecha, meses) {
    var nuevaFecha = new Date(fecha);
    nuevaFecha.setMonth(nuevaFecha.getMonth() + meses);
    return nuevaFecha;
}
function modificarSemanas(fecha, semanas) {
    return modificarDias(fecha, semanas * 7);
}
function modificarAnios(fecha, anios) {
    var nuevaFecha = new Date(fecha);
    nuevaFecha.setFullYear(nuevaFecha.getFullYear() + anios);
    return nuevaFecha;
}
function obtenerDiasEnMes(anio, mes) {
    return new Date(anio, mes + 1, 0).getDate();
}
function diasRestantesEnMes(fecha) {
    var ultimoDia = new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0);
    return (ultimoDia - fecha) / (1000 * 60 * 60 * 24);
}
function obtenerUltimoDiaDelMes(fecha) {
    return new Date(fecha.getFullYear(), fecha.getMonth() + 1, 0);
}
function formatearFecha(fecha, formato = "dd-mm-aaaa") {
    var dia = String(fecha.getDate()).padStart(2, '0');
    var mes = String(fecha.getMonth() + 1).padStart(2, '0');
    var anio = fecha.getFullYear();

    return formato.replace('dd', dia).replace('mm', mes).replace('aaaa', anio);
}

function dejar_solo_numero(texto){
    let texto_final = texto;
    const literales = ['€', '-', '%', '(', ')', '.', '0']
    literales.forEach(x=>{
        texto_final = texto_final.replaceAll(x,'').trim()
    })
    return texto_final
}

function validateEmail(id) {
        var email = $('#'+id).val(); 
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
        return emailRegex.test(email)
    // return typeof document.getElementById(id).checkValidity === 'function' ? document.getElementById(id).checkValidity() : /\S+@\S+\.\S+/.test(value);
}

/* funcion que inicialice un input date-range */
function inicializarDateRange(dateInput, column) {
    dateInput.daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango personalizado',
            weekLabel: 'S',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        },
        opens: 'left',
        autoUpdateInput: false, // Para que no se muestre nada hasta que se seleccione un rango
    });

    dateInput.on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        // Enviar las fechas como un único valor separado por "|"
        if (column && typeof column.search === 'function') {
            column.search(startDate + '|' + endDate).draw();
        }
        $(this).val(startDate + ' - ' + endDate);
    });

    // Limpiar el campo si el usuario cancela la selección de rango
    dateInput.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        if (column && typeof column.search === 'function') {
            column.search('').draw();
        }
    });
}