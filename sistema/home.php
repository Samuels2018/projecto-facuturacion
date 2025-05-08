<?php 
    include_once("conf/conf.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Holded</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ENLACE_WEB; ?>bootstrap/home.css">

</head>
<body>
    <div class="container-fluid">
        <div class="left-panel">
            <center><img class="logo-svg" src="http://localhost/factura_electronica/sistema/bootstrap/assets/img/logo.svg"></center>
            <h1>El software de gestión empresarial inteligente para PYMEs.</h1>
            <p>Holded es la solución en la nube que tiene todo lo que necesitas para gestionar tu negocio, en cualquier momento y lugar.</p>
        </div>
        <div class="right-panel">
            <img style="max-width: 60px;" src="http://localhost/factura_electronica/sistema/bootstrap/assets/img/logo.svg">

            <div class="mx-auto" style="max-width: 70%;">
                <h1>Prueba Holded gratis por 14 días</h1>
                <p>No se necesita tarjeta de crédito. Uso ilimitado. Sin compromiso.</p>
                <button type="button" class="btn btn-light btn-block mb-2">
                    <i class="fab fa-google"></i> Regístrate con Google
                </button>
                <div class="or-divider">O</div>
               <form id="loginForm" autocomplete="off">
                    <input type="text" autocomplete="off" class="form-control" placeholder="Nombre" required>
                    <input type="email" autocomplete="off" class="form-control" placeholder="Correo electrónico" required>
                    <div class="input-group mb-3 position-relative">
                        <input type="password" id="password" autocomplete="off" class="form-control" placeholder="Contraseña" required>
                        <span class="toggle-password fas fa-eye"></span>
                    </div>
                    <ul class="password-requirements" id="passwordRequirements">
                        <li id="minLength" class="invalid">Al menos 8 caracteres</li>
                        <li id="capitalLetter" class="invalid">Una letra mayúscula</li>
                        <li id="lowercaseLetter" class="invalid">Una letra minúscula</li>
                        <li id="number" class="invalid">Un número</li>
                    </ul>   
                    <div class="input-group mb-3">
                        <div class="input-group input-group-sm">
                            <input id="mobile" type="tel" name="mobile" autocomplete="off" class="form-control" autofocus required style="min-width:564px;">
                        </div>
                    </div>
                    <div class="spinner" id="spinner"></div>
                    <button type="submit" class="btn btn-primary btn-block">Comienza tu prueba de 14 días</button>
                </form>

                <p class="mt-3">Al continuar, aceptas estos <a href="#">Términos</a>, <a href="#">Política de Datos</a> y <a href="#">Política de Cookies</a>.</p>
                <p class="text-right"><a href="#">¿Ya tienes una cuenta? Iniciar sesión</a></p>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/intlTelInput.min.js"></script>
</body>
</html>


<script type="text/javascript">
    
     $(function () {
            var code = "+19876543210"; // Assigning value from model.
            $('#mobile').val(code);
            $('#mobile').intlTelInput({
                autoHideDialCode: true,
                autoPlaceholder: "ON",
                dropdownContainer: document.body,
                formatOnDisplay: true,
               // hiddenInput: "full_number",
                initialCountry: "us",
              //  nationalMode: true,
                placeholderNumberType: "MOBILE",
                preferredCountries: ['us','gb','in'],
                separateDialCode: true
            });
            $('#btn-submit').on('click', function () {
                var code = $("#mobile").intlTelInput("getSelectedCountryData").dialCode;
                var phoneNumber = $('#mobile').val();
              //  $('#mobile').val(code+phoneNumber);
                //  alert('Country Code : ' + code + '\nPhone Number : ' + phoneNumber );
                document.getElementById("code").innerHTML = code;
                document.getElementById("mobile-number").innerHTML = phoneNumber;
            });


             $('#loginForm').on('submit', function(e) {
                    e.preventDefault(); // Evita el envío del formulario para mostrar el efecto de carga
                    $('#spinner').show();
                    $('button[type="submit"]').addClass('loading').text('Cargando...');

                    // Simula una solicitud de inicio de sesión
                    setTimeout(function() {
                        $('#spinner').hide();
                        $('button[type="submit"]').removeClass('loading').text('Comienza tu prueba de 14 días');
                        // Aquí puedes redirigir al usuario o mostrar un mensaje de éxito
                    }, 3000); // Simula 3 segundos de tiempo de carga
             });


               /*$('#password').on('focus', function() {  
                    $('#passwordRequirements').fadeIn();
                });

                $('#password').on('blur', function() {
                    $('#passwordRequirements').fadeOut();
                });*/

                $('#password').on('input', function() {
                    var password = $(this).val();
                    var minLength = password.length >= 8;
                    var capitalLetter = /[A-Z]/.test(password);
                    var lowercaseLetter = /[a-z]/.test(password);
                    var number = /[0-9]/.test(password);

                    $('#minLength').toggleClass('valid', minLength).toggleClass('invalid', !minLength);
                    $('#capitalLetter').toggleClass('valid', capitalLetter).toggleClass('invalid', !capitalLetter);
                    $('#lowercaseLetter').toggleClass('valid', lowercaseLetter).toggleClass('invalid', !lowercaseLetter);
                    $('#number').toggleClass('valid', number).toggleClass('invalid', !number);
                });

                $('.toggle-password').on('click', function() {
                    var input = $('#password');
                    var icon = $(this);
                    if (input.attr('type') === 'password') {
                        input.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        input.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });


        });

</script>