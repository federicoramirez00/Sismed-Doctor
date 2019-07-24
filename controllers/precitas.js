$(document).ready(function () {
    readPreCita();
})

const apiPre = 'http://localhost/php/api/citas.php?action=';

// Función para obtener y mostrar las categorías de productos
function readPreCita() {
    $.ajax({
        url: apiPre + 'readPreCitas',
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
                        content += `
                    <div class="card col s12 m3">
                    <div class="card-image waves-effect waves-block waves-light">
                        <img class="activator" src="../www/img/foto.jpg" height="300">
                    </div>
                    <div class="card-action">
                        <span class="card-title activator grey-text text-darken-4">${row.nombre_paciente} ${row.apellido_paciente}<i
                                class="material-icons right">more_vert</i></span>
                        <p>Este paciente desea hacer una cita</p>
                        <!--<a href="#" onclick="modalUpdate(${row.id_cita})" class="green-text tooltipped" data-tooltip="Aceptar"><i class="material-icons">check</i></a>
                        <a href="#" onclick="confirmDelete(${row.id_cita})" class="red-text tooltipped" data-tooltip="Cancelar"><i class="material-icons">close</i></a>-->
                        ${
                            row.id_estado == 1
                            ? '<div class="card-action"><a class="green-text tooltipped" onclick="aceptarPreCita(' +row.id_cita +')"><i class="material-icons left">check</i> Aceptar cita</a></div>'
                            : ""
                            }
                        ${row.id_estado == 1
                                ? '<div class="card-action"><a class="red-text tooltipped" onclick="cancelarCita(' + row.id_cita + ')"><i class="material-icons left">close</i> Rechazar cita</a></div>'
                                : ""
                            }
                    </div>
                    <div class="card-reveal">
                        <span class="card-title activator grey-text text-darken-4">${row.nombre_paciente} ${row.apellido_paciente}<i
                                class="material-icons right">close</i></span>
                        <p><b>Fecha: </b>${row.fecha_cita}</p>
                        <p><b>Hora: </b>${row.hora_cita}</p>
                    </div>
                </div>
                    `;
                    });
                    $('#title').text('Nuestro catálogo');
                    $('#pre-citas').html(content);
                    $('.tooltipped').tooltip();
                } else {
                    $('#title').html('<i class="material-icons small">cloud_off</i><span class="red-text">' + result.exception + '</span>');
                    $('#catalogo').html('');
                }
            } else {
                console.log(response);
            }
        })
        .fail(function (jqXHR) {
            // Se muestran en consola los posibles errores de la solicitud AJAX
            console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
        });
}

function cancelarCita(id) {
    swal({
        title: 'Advertencia',
        text: 'Está seguro que desea rechazar la cita?',
        icon: 'warning',
        buttons: ['Cancelar', 'Aceptar'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        if (value) {
            $.ajax({
                url: apiPre + "cancelarPreCita",
                type: "post",
                data: {
                    id_cita: id
                },
                datatype: "json"
            })
                .done(function (response) {
                    //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                    if (isJSONString(response)) {
                        const result = JSON.parse(response);
                        //Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                        if (result.status) {
                            readPreCita();
                            swal({
                                title: 'Enhorabuena',
                                text: 'Se ha rechazado la cita',
                                icon: 'info',
                                button: 'Aceptar',
                                closeOnClickOutside: false,
                                closeOnEsc: false
                            });
                        }
                    } else {
                        console.log(response);
                    }
                })
                .fail(function (jqXHR) {
                    //Se muestran en consola los posibles errores de la solicitud AJAX
                    console.log("Error: " + jqXHR.status + " " + jqXHR.statusText);
                });
        }
    });
}

function aceptarPreCita(id) {
    swal({
        title: 'Advertencia',
        text: '¿Está seguro que puede atender la cita?',
        icon: 'warning',
        buttons: ['Cancelar', 'Aceptar'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        if (value) {
            $.ajax({
                url: apiPre + "aceptarPreCita",
                type: "post",
                data: {
                    id_cita: id
                },
                datatype: "json"
            })
                .done(function (response) {
                    //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                    if (isJSONString(response)) {
                        const result = JSON.parse(response);
                        //Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                        if (result.status) {
                            readPreCita();
                            swal({
                                title: 'Enhorabuena',
                                text: 'Se ha agendado la cita',
                                icon: 'info',
                                button: 'Aceptar',
                                closeOnClickOutside: false,
                                closeOnEsc: false
                            });
                        }
                    } else {
                        console.log(response);
                    }
                })
                .fail(function (jqXHR) {
                    //Se muestran en consola los posibles errores de la solicitud AJAX
                    console.log("Error: " + jqXHR.status + " " + jqXHR.statusText);
                });
        }
    });
}