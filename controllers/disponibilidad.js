$(document).ready(function () {
    showTable();
})

const apiDisponibilidad = 'http://localhost/php/api/disponibilidad.php?action=';
const apiDias = 'http://localhost/php/api/dias.php?action=';

function showSelectDias(idSelect, value)
{
    $.ajax({
        url: apiDias + 'read',
        type: 'post',
        data: null,
        datatype: 'json'
    })
    .done(function(response){
        //Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            console.log(result);
            //Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                let content = '';
                if (!value) {
                    content += '<option value="" disabled selected>Seleccione una opción</option>';
                }
                result.dataset.forEach(function(row){
                    if (row.categoria != value) {
                        content += `<option value="${row.id_dia}">${row.dia}</option>`;
                    } else {
                        content += `<option value="${row.id_dia}" selected>${row.dia}</option>`;
                    }
                });
                $('#' + idSelect).html(content);
            } else {
                $('#' + idSelect).html('<option value="">No hay opciones</option>');
            }
            $('select').formSelect();
        } else {
            console.log(response);
        }
    })
    .fail(function(jqXHR){
        //Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
}

//Función para llenar la tabla con los registros
function fillTable(rows) {
    let content = '';
    //Se recorren las filas para armar el cuerpo de la tabla y se utiliza comilla invertida para escapar los caracteres especiales
    rows.forEach(function (row) {
        //(row.id_estado == 1) ? icon = '1' : icon = '2';
        content += `
            <tr>
                <td>${row.id_disponibilidad}</td>
                <td>${row.dia}</td>
                <td>${row.hora_inicio}</td> 
                <td>${row.hora_fin}</td>
                <td>${row.id_doctor}</td>
                <td>
                    <a href="#" onclick="modalUpdate(${row.id_disponibilidad})" class="green-text tooltipped" data-tooltip="Reprogramar"><i class="material-icons">history</i></a>
                    <a href="#" onclick="confirmDelete(${row.id_disponibilidad})" class="red-text tooltipped" data-tooltip="Cancelar"><i class="material-icons">close</i></a>
                </td>
            </tr>
        `;
    });
    $('#tbody-disponibilidad').html(content);
    $("#tabla-disponibilidad").DataTable({
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
    $.ajax({
        url: apiDisponibilidad + 'readDisponibilidad',
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
                    sweetAlert(4, result.exception, null);
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
}

// Función para mostrar formulario con registro a modificar
function modalUpdate(id) {
    $.ajax({
        url: apiDisponibilidad + 'get',
        type: 'post',
        data: {
            id_disponibilidad: id
        },
        datatype: 'json'
    })
        .done(function (response) {
            // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
            if (isJSONString(response)) {
                const result = JSON.parse(response);
                // Se comprueba si el resultado es satisfactorio para mostrar los valores en el formulario, sino se muestra la excepción
                if (result.status) {
                    console.log(result);
                    $('#form-update')[0].reset();
                    $('#id_disponibilidad').val(result.dataset.id_disponibilidad);
                    console.log(apiDias);
                    //fillSelect(apiDias, 'update_dia', result.dataset.id_dia);
                    showSelectDias('readDias', result.dataset.id_dia);
                    $('#update_inicio').val(result.dataset.hora_inicio);
                    $('#update_fin').val(result.dataset.hora_fin);
                    $('#update_descripcion').val(result.dataset.descripcion_producto);
                    M.updateTextFields();
                    $('#modal-update').modal('open');
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
}

// Función para modificar un registro seleccionado previamente
$('#form-update').submit(function () {
    event.preventDefault();
    $.ajax({
        url: apiDisponibilidad + 'update',
        type: 'post',
        data: new FormData($('#form-update')[0]),
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
                    $('#modal-update').modal('close');
                    $("#tabla-disponibilidad").DataTable();
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