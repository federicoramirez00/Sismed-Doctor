$(document).ready(function () {
    countNotificaciones();
})

const apiNotificacion = 'http://localhost/php/api/citas.php?action=';

function countNotificaciones() {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: apiNotificacion + 'countPreCitas&idDoctor=' + localStorage.getItem('id'),
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
                        let content = '';
                        result.dataset.forEach(function (row) {
                            if (row.Citas > 0) {
                                content += `
                            <a href="../www/notificaciones.html"><i class="material-icons right">notifications</i><span id="span" class="new badge red right" data-badge-caption="">${row.Citas}</span></a>
                        `;
                            } else {
                                content += `
                            <a href="../www/notificaciones.html"><i class="material-icons right">notifications</i></a>
                        `;
                            }
                        });
                        //$('#title').text('Nuestro catálogo');
                        $('#nav').html(content);
                        $('.tooltipped').tooltip();
                    } else {
                        //M.toast({html: 'I am a toast', classes: 'rounded'})
                        swal({
                            title: 'Aviso',
                            text: 'No hay nuevas notificaciones de citas',
                            icon: 'info',
                            button: 'Aceptar',
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        })
                            .then(function (value) {
                                if (value) {
                                    location.href = 'citas.html'
                                    //sweetAlert(1, 'Perfil modificado correctamente', 'index.html');
                                }
                            });
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