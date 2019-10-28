$(document).ready(function () {
    showTable();
    countCitas();
    getProximaCita();
})

const apiCitas = 'http://localhost/php/api/citas.php?action=';

//Función para llenar la tabla con los registros
function fillTable(rows) {
    let content = '';
    //Se recorren las filas para armar el cuerpo de la tabla y se utiliza comilla invertida para escapar los caracteres especiales
    rows.forEach(function (row) {
        //(row.id_estado == 1) ? icon = '1' : icon = '2';
        content += `
            <tr>
                <td>${row.id_cita}</td>
                <td>${row.nombre_paciente}</td>
                <td>${row.apellido_paciente}</td> 
                <td>${row.fecha_cita}</td>
                <td>${row.hora_cita}</td>
                <td class="green-text">${row.estado}</td>
                <td>
                    <a href="#" onclick="realizarCita(${row.id_cita})" class="green-text tooltipped" data-tooltip="Realizar"><i class="material-icons">check</i></a>
                    <!--<a href="#" onclick="modalUpdate(${row.id_cita})" class="green-text tooltipped" data-tooltip="Reprogramar"><i class="material-icons">history</i></a>-->
                    <a href="#" onclick="confirmDelete(${row.id_cita})" class="red-text tooltipped" data-tooltip="Cancelar"><i class="material-icons">close</i></a>
                </td>
            </tr>
        `;
    });
    $('#tabla-body').html(content);
    $("#tabla-citas").DataTable({
        responsive: true,
        retrieve: true,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    $('.tooltipped').tooltip();
}

function showTable() {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: apiCitas + 'readCita&idDoctor='+localStorage.getItem('id'),
            type: 'post',
            data: null,
            datatype: 'json'
        })
            .done(function (response) {
                // Se verifica si la respuesta de la api es una cadena JSON, sino se muestra el resultado en consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                    if (!result.status) {
                        //sweetAlert(4, result.exception, null);
                        M.toast({html: 'No hay citas programadas. Dirigite a tus notificaciones', classes: 'rounded'});
                    }
                    fillTable(result.dataset);
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

function countCitas() {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: apiCitas + 'count&idDoctor='+localStorage.getItem('id'),
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
                                <div class="col s12 m6">
                                    <div class="card indigo">
                                        <div class="card-content white-text">
                                        <span class="card-title"><strong>${row.citas}</strong></span>
                                        <p>Citas programadas para el día de hoy.</p>
                                        </div>
                                    </div>
                                </div>
                        `;
                        });
                        $('#card-citas').html(content);
                        $('.tooltipped').tooltip();
                    } else {
                        swal({
                            title: 'Aviso',
                            text: 'No hay nuevas notificaciones de citas',
                            icon: 'info',
                            button: 'Aceptar',
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        })
                        .then(function(value){
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

function realizarCita(id) {
    swal({
        title: 'Advertencia',
        text: '¿Está seguro que la cita ha sido realizada?',
        icon: 'info',
        buttons: ['Cancelar', 'Aceptar'],
        closeOnClickOutside: false,
        closeOnEsc: false
    }).then(function (value) {
        if (value) {
            $.ajax({
                url: apiCitas + 'realizarCita',
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
                            showTable();
                            /*swal({
                                title: 'Enhorabuena',
                                text: 'Se ha agendado la cita',
                                icon: 'info',
                                button: 'Aceptar',
                                closeOnClickOutside: false,
                                closeOnEsc: false
                            });*/
                            //sweetAlert(1, 'Cita realizada', 'citas.html');
                            M.toast({html: 'Cita realizada', classes: 'rounded'});
                            $("#tabla-citas").DataTable().destroy();
                            showTable();
                            location.href = 'citas.html';
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

function getProximaCita() {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: apiCitas + 'getProximaCita&idDoctor='+localStorage.getItem('id'),
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
                            if (row.proximaCita != null) {
                                content += `
                                <div class="col s12 m6">
                                    <div class="card indigo">
                                        <div class="card-content white-text">
                                        <p>Próxima cita</p>
                                        <span class="card-title"><strong>${row.proximaCita}</strong></span>
                                        </div>
                                    </div>
                                </div>
                        `;
                            } else {
                                content += `
                                <div class="col s12 m6">
                                    <div class="card indigo">
                                        <div class="card-content white-text">
                                        <p>Próxima cita</p>
                                        <span class="card-title"><strong>No hay citas próximas</strong></span>
                                        </div>
                                    </div>
                                </div>
                        `;
                            }
                        });
                        $('#card-proxima').html(content);
                        $('.tooltipped').tooltip();
                    } else {
                        swal({
                            title: 'Aviso',
                            text: 'No hay nuevas notificaciones de citas',
                            icon: 'info',
                            button: 'Aceptar',
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        })
                        .then(function(value){
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

function modalCreate()
{
    $('#form-create')[0].reset();
    //fillSelect(anexodoct + 'fill','create_doctor', null)
    fillSelect(anexopaci + 'fillpaciente', 'create_paciente',null);
    $('#modal-create').modal('show');
}

// Función para mostrar formulario con registro a modificar
function modalUpdate(id) {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: apiCitas + 'get&idDoctor='+localStorage.getItem('id'),
            type: 'post',
            data: {
                id_cita: id
            },
            datatype: 'json'
        })
            .done(function (response) {
                // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio para mostrar los valores en el formulario, sino se muestra la excepción
                    if (result.status) {
                        //console.log(result);
                        $('#form-reprogramar')[0].reset();
                        $('#cita').val(result.dataset.id_cita);
                        $('#update_fecha').val(result.dataset.fecha_cita);
                        $('#update_hora').val(result.dataset.hora_cita);
                        M.updateTextFields();
                        $('#update-cita').modal('open');
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

// Función para modificar un registro seleccionado previamente
$('#form-reprogramar').submit(function () {
    event.preventDefault();
    $.ajax({
        url: apiCitas + 'reschedule',
        type: 'post',
        data: $('#form-reprogramar').serialize(),
        datatype: 'json'
    })
        .done(function (response) {
            // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
            if (isJSONString(response)) {
                const result = JSON.parse(response);
                // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                if (result.status) {
                    $('#update-cita').modal('close');
                    showTable();
                    sweetAlert(1, result.message, null);
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
})

// Función para eliminar un registro seleccionado
function confirmDelete(id)
{
    swal({
        title: 'Advertencia',
        text: '¿Está seguro que desea borrar la cita seleccionada?',
        icon: 'warning',
        buttons: ['Cancelar', 'Aceptar'],
        closeOnClickOutside: false,
        closeOnEsc: false
    })
    .then(function(value){
        if (value) {
            $.ajax({
                url: apiCitas + 'delete',
                type: 'post',
                data:{
                    id_cita: id
                },
                datatype: 'json'
            })
            .done(function(response){
                // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
                if (isJSONString(response)) {
                    const result = JSON.parse(response);
                    // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                    if (result.status) {
                        $("#tabla-citas").DataTable().destroy();
                        showTable();
                        sweetAlert(1, result.message, null);
                    } else {
                        sweetAlert(2, result.exception, null);
                    }
                } else {
                    console.log(response);
                }
            })
            .fail(function(jqXHR){
                // Se muestran en consola los posibles errores de la solicitud AJAX
                console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
            });
        }
    });
}
