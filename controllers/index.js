$(document).ready(function () {
    checkUsuarios();
})

//Constante para establecer la ruta y parámetros de comunicación con la API
const apiSesion = 'http://localhost/php/api/doctores.php?action=';

//Función para verificar si existen usuarios en el sitio privado
function checkUsuarios() {
    $.ajax({
        url: apiSesion + 'read',
        type: 'post',
        data: null,
        datatype: 'json'
    })
        .done(function (response) {
            //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
            if (isJSONString(response)) {
                const dataset = JSON.parse(response);
                //Se comprueba que no hay usuarios registrados para redireccionar al registro del primer usuario
                if (dataset.status == 2) {
                    sweetAler(3, dataset.exception, 'index.html');
                }
            } else {
                console.log(response);
            }
        })
        .fail(function (jqXHR) {
            //Se muestran en consola los posibles errores de la solicitud AJAX
            console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
        });
}
var cont = 0;
//Función para validar el usuario al momento de iniciar sesión
$('#form-sesion').submit(function () {

    event.preventDefault();
    $.ajax({
        url: apiSesion + 'login',
        type: 'post',
        data: $('#form-sesion').serialize(),
        datatype: 'json'
    })
        .done(function (response) {
            //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
            if (isJSONString(response)) {
                const dataset = JSON.parse(response);
                //Se comprueba si la respuesta es satisfactoria, sino se muestra la excepción
                if (dataset.status) {
                    localStorage.setItem('id', dataset.id);
                    sweetAlert(1, 'Autenticación correcta', 'dashboard.html');
                } else {
                    if (cont >= 3) {
                        $.ajax({
                            url: apiSesion + 'block',
                            type: 'post',
                            data: {
                                usuario: $('#usuario').val()
                            },
                            datatype: 'json'
                        })
                            .done(function (response) {
                                if (isJSONString(response)) {
                                    const result = JSON.parse(response);
                                    if (result.status) {
                                        sweetAlert(4, result.message, null);
                                    }
                                }
                            })
                    } else {
                        sweetAlert(2, dataset.exception, null);
                        cont++;
                    }
                }
            } else {
                console.log(response);
            }
        })
        .fail(function (jqXHR) {
            //Se muestran en consola los posibles errores de la solicitud AJAX
            console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
        });
});