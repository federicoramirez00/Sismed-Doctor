$(document).ready(function () {
    modalProfile();
})

const api = 'http://localhost/php/api/doctores.php?action=';

$('#form-registro').submit(function () {
    event.preventDefault();
    $.ajax({
        url: api + 'register',
        type: 'post',
        data: $('#form-registro').serialize(),
        datatype: 'json'
    })
        .done(function (response) {
            //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
            if (isJSONString(response)) {
                const dataset = JSON.parse(response);
                //Se comprueba si la respuesta es satisfactoria, sino se muestra la excepción
                if (dataset.status) {
                    sweetAlert(1, 'Usuario registrado correctamente', 'index.html');
                } else {
                    sweetAlert(2, dataset.exception, null);
                }
            } else {
                console.log(response);
            }
        })
        .fail(function (jqXHR) {
            //Se muestran en consola los posibles errores de la solicitud AJAX
            console.log('Errors: ' + jqXHR.status + ' ' + jqXHR.statusText);
        });
})

// Función para mostrar formulario de perfil de usuario
function modalProfile() {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: api + 'readProfile&idDoctor=' + localStorage.getItem('id'),
            type: 'post',
            data: null,
            datatype: 'json'
        })
            .done(function (response) {
                // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                    if (result.status) {
                        $('#foto').attr('src', '../www/img/fotos/' + result.dataset.foto_doctor);
                        $('#profile_nombre').val(result.dataset.nombre_doctor);
                        $('#profile_apellido').val(result.dataset.apellido_doctor);
                        $('#profile_correo').val(result.dataset.correo_doctor);
                        $('#profile_usuario').val(result.dataset.usuario_doctor);
                        $('#profile_fecha').val(result.dataset.fecha_nacimiento);
                        $('#profile_telefono').val(result.dataset.telefono_doctor);
                        //$('#foto_usuario').val(result.dataset.foto_doctor);
                        $('#modal-profile').modal('open');
                    } else {
                        sweetAlert(2, result.exception, null);
                    }
                } else {
                    console.log(response);
                }
            })
            .fail(function (jqXHR) {
                // Se muestran en consola los posibles errores de la solicitud AJAX
                console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
            });
    } else {
        location.href = 'index.html';
    }
}

// Función para editar el perfil del usuario que ha iniciado sesión
$('#form-profile').submit(function () {
    if (localStorage.getItem('id') != null) {
        event.preventDefault();
        $.ajax({
            url: api + 'editProfile&idDoctor=' + localStorage.getItem('id'),
            type: 'post',
            data: new FormData($('#form-profile')[0]),
            datatype: 'json',
            cache: false,
            contentType: false,
            processData: false
        })
            .done(function (response) {
                // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                    if (result.status) {
                        $('#modal-profile').modal('close');
                        sweetAlert(1, result.message, 'perfil.html');
                        /*M.toast({html: result.message, classes: 'rounded'});
                        location.href = 'perfil.html';*/
                    } else {
                        M.toast({ html: result.exception, classes: 'rounded' });
                    }
                } else {
                    console.log(response);
                }
            })
            .fail(function (jqXHR) {
                // Se muestran en consola los posibles errores de la solicitud AJAX
                console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
            });
    } else {
        location.href = 'index.html';
    }
})

// Función para cambiar la contraseña del usuario que ha iniciado sesión
$('#form-password').submit(function () {
    if (localStorage.getItem('id') != null) {
        event.preventDefault();
        $.ajax({
            url: api + 'password&idDoctor=' + localStorage.getItem('id'),
            type: 'post',
            data: $('#form-password').serialize(),
            datatype: 'json'
        })
            .done(function (response) {
                // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                    if (result.status) {
                        $('#modal-password').modal('close');
                        //sweetAlert(1, 'Contraseña modificada exitosamente', 'perfil.html');
                        M.toast({ html: 'Contraseña modificada exitosamente', classes: 'rounded' });
                    } else {
                        M.toast({ html: result.exception, classes: 'rounded' });
                    }
                } else {
                    console.log(response);
                }
            })
            .fail(function (jqXHR) {
                // Se muestran en consola los posibles errores de la solicitud AJAX
                console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
            });
    } else {
        location.href = 'index.html';
    }
})