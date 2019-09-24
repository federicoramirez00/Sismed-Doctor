$(document).ready(function () {
    showTable();
    showTableAll();
})

const apiPacientes = 'http://localhost/php/api/pacientes.php?action=';

//Función para llenar la tabla con los registros
function fillTable(rows) {
    let content = '';
    //Se recorren las filas para armar el cuerpo de la tabla y se utiliza comilla invertida para escapar los caracteres especiales
    rows.forEach(function (row) {
        //(row.id_estado == 1) ? icon = '1' : icon = '2';
        content += `
            <tr>
                <td>${row.id_paciente}</td>
                <td>${row.Nombre}</td>
                <td>${row.telefono_paciente}</td>
                <td>${row.UltimaCita}</td>
                <td>${row.fecha_nacimiento}</td>
                <td>${row.peso_paciente} lb</td>
                <td>${row.estatura_paciente} cm</td>
                <!--<td>
                    <a href="#" onclick="modalUpdate(${row.id_cita})" class="green-text tooltipped" data-tooltip="Reprogramar"><i class="material-icons">history</i></a>
                    <a href="#" onclick="confirmDelete(${row.id_cita})" class="red-text tooltipped" data-tooltip="Cancelar"><i class="material-icons">close</i></a>
                </td>-->
            </tr>
        `;
    });
    $('#tabla-body').html(content);
    $("#tabla-asignados").DataTable({
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
            url: apiPacientes + 'readByDoctor&idDoctor='+localStorage.getItem('id'),
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
                        M.toast({html: result.exception, classes: 'rounded'});
                        //sweetAlert(4, result.exception, null);
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

//Función para llenar la tabla con los registros
function fillTableAll(rows) {
    let content = '';
    //Se recorren las filas para armar el cuerpo de la tabla y se utiliza comilla invertida para escapar los caracteres especiales
    rows.forEach(function (row) {
        //(row.id_estado == 1) ? icon = '1' : icon = '2';
        content += `
            <tr>
                <td>${row.id_paciente}</td>
                <td>${row.nombre_paciente}</td>
                <td>${row.apellido_paciente}</td>
                <td>${row.telefono_paciente}</td>
                <td>${row.fecha_nacimiento}</td>
                <td>${row.peso_paciente} lb</td>
                <td>${row.estatura_paciente} cm</td>
                <!--<td>
                    <a href="#" onclick="modalUpdate(${row.id_cita})" class="green-text tooltipped" data-tooltip="Reprogramar"><i class="material-icons">history</i></a>
                    <a href="#" onclick="confirmDelete(${row.id_cita})" class="red-text tooltipped" data-tooltip="Cancelar"><i class="material-icons">close</i></a>
                </td>-->
            </tr>
        `;
    });
    $('#table-body').html(content);
    $("#tabla-pacientes").DataTable({
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

function showTableAll() {
    if (localStorage.getItem('id') != null) {
        $.ajax({
            url: apiPacientes + 'read',
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
                        M.toast({html: result.exception, classes: 'rounded'});
                        //sweetAlert(4, result.exception, null);
                    }
                    fillTableAll(result.dataset);
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

// Función para crear un nuevo registro
$('#form-create').submit(function () {
    event.preventDefault();
    $.ajax({
        url: apiPacientes + 'createApp',
        type: 'post',
        data: new FormData($('#form-create')[0]),
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
                if (result.session) {
                    if (result.status) {
                        $('#modal-create').modal('close');
                        M.toast({html: result.message, classes: 'rounded'});
                        $("#tabla-pacientes").DataTable().destroy();
                        $('#form-create')[0].reset();
                        showTable();
                        
                    } else {
                        M.toast({html: result.exception, classes: 'rounded'});
                    }
                } else {
                    console.log(response);
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