function Idioma(id){

    
    fetch(ENLACE_WEB+'mod_idiomas/json/actualizar.idioma.json.php?idioma='+id)
        .then(response => response.json())
        .then(data => {
            
            if  (data.exito==1){   Snackbar.show({ text: 'Refrescando website' , pos: 'bottom-right'  , actionTextColor: '#00C851',   backgroundColor: '#43A047', duration: 100000});  location.reload();  }
                else { Snackbar.show({ text: "Se encontro un error"  , pos: 'bottom-right', duration: 100000});  }
            
                console.log(data);
         })
        .catch(error => {
            
            // Manejar errores aquí
            
            console.error('Error:', error);
            Snackbar.show({ text: error  , pos: 'bottom-right', duration: 100000}); 

        });
 


}
