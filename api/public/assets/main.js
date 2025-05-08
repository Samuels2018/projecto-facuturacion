



function showErrorToast(message) {
  // Crear el elemento del toast
  var toast = document.createElement('div');
  toast.className = 'toast align-items-center text-white bg-danger border-0 position-fixed start-50 translate-middle-x';
  toast.setAttribute('role', 'alert');
  toast.setAttribute('aria-live', 'assertive');
  toast.setAttribute('aria-atomic', 'true');

  // Crear el contenido del toast
  toast.innerHTML = `
      <div class="d-flex">
          <div class="toast-body">
              ${message}
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
  `;

  // Calcular el desplazamiento en función de la cantidad de toasts actuales
  const toastContainer = document.getElementById('toastContainer');
  const offset = toastContainer.children.length * 80; // Ajusta "80" según el espacio deseado entre toasts

  // Aplicar el desplazamiento al estilo del toast
  toast.style.top = `${offset}px`;

  // Agregar el toast al contenedor
  toastContainer.appendChild(toast);

  // Configurar el toast para que se autodestruya después de 5 segundos
  var bootstrapToast = new bootstrap.Toast(toast, {
      delay: 5000,
      autohide: true
  });

  // Mostrar el toast
  bootstrapToast.show();

  // Eliminar el elemento del DOM cuando el toast se oculta
  toast.addEventListener('hidden.bs.toast', function () {
      toast.remove();
  });

}


function extractNumbersAsSingleLine(text) {

    text.replace(/^\s+|\s+$/g, '')
    .replace(/\t/g, ' ')
    .replace(/ +/g, ' '); 

    const numbers = text.match(/\d+/g);
    
    if (!numbers) return '';
    
    return numbers.join('');
}



function onSendFormPost(id, success) {

  const element_form = document.querySelector(id)

  element_form.addEventListener("submit", async function (event) {
    // Evitar el envío del formulario tradicional
    event.preventDefault();

    const url = element_form.action
    const method = element_form.method.toLocaleUpperCase()

    // Capturar los datos del formulario
    const formData = new FormData(this);

    try {
      // Realizar la solicitud con fetch
      const response = await fetch(url, {
        method,
        body: formData,
        headers: {
          'Accept': 'application/json'
        }
      });

      // Verificar si la solicitud fue exitosa
      if (response.ok) {
        const data = await response.json();
        console.log("Éxito:", data);

        if(data.code == 422){

          data.data.errors.forEach(e=>{
              showErrorToast(e)
          })
        }
        else if(data.code == 400){

            data.data.errors.forEach(e=>{
                showErrorToast(e)
            })
        } 


        success(data)

      } else {
        console.error("Error en la solicitud:", response.statusText);
      }
    } catch (error) {
      console.error(error);

    }
  });

}

