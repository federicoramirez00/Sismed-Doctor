// Constante para establecer la ruta y parámetros de comunicación con la API
const apiLogin = '../api/doctores.php?action=';

// Función para validar el usuario al momento de iniciar sesión
$('#form-login').submit(function()
{
    event.preventDefault();
    $.ajax({
        url: apiLogin + 'login',
        type: 'post',
        data: $('#form-login').serialize(),
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const dataset = JSON.parse(response);
            // Se comprueba si la respuesta es satisfactoria, sino se muestra la excepción
            if (dataset.status) {
                sweetAlert(1, dataset.message, 'dashboard.html');
            } else {
                sweetAlert(2, dataset.exception, null);
            }
        } else {
            console.log(response);
        }
    })
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
})