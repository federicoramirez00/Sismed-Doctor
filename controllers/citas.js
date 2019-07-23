$(document).ready(function () {
    showTable();
})

const apiCita = 'http://localhost/php/api/citas.php?action=';

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
                <td>${row.id_estado}</td>
                <td>
                    <a href="#" onclick="modalUpdate(${row.id_cita})" class="green-text tooltipped" data-tooltip="Reprogramar"><i class="material-icons">history</i></a>
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
    $.ajax({
        url: apiCita + 'readCita',
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

function getProducto(id) {
    $.ajax({
        url: api + 'detailProducto',
        type: 'post',
        data: {
            id_cita: id
        },
        datatype: 'json'
    })
        .done(function (response) {
            // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
            if (isJSONString(response)) {
                const result = JSON.parse(response);
                // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
                if (result.status) {
                    let content = `
                <div class="col s12 m6">
                <div class="card">
                    <div class="card-image">
                        <img src="../www/img/doctores.png">
                        <span class="card-title white-text text-darken-4"></span>
                    </div>
                    <div class="card-content indigo white-text">
                        <span class="card-title"><strong>${result.dataset.citas}</strong></span>
                        <p>Citas programadas para hoy.</p>
                    </div>
                    <div class="card-action indigo">
                        <a href="../www/citas.html">Ver todas las citas</a>
                    </div>
                </div>
            </div>
                `;
                    $('#title').text('Detalles del producto');
                    $('#citas').html(content);
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

// Función para mostrar formulario con registro a modificar
function modalUpdate(id)
{
    $.ajax({
        url: apiCita + 'get&id_cita='+id,
        type: 'post',
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            // Se comprueba si el resultado es satisfactorio para mostrar los valores en el formulario, sino se muestra la excepción
            if (result.status) {
                $('#id_cita').val(result.dataset.id_cita);
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
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
}

// Función para modificar un registro seleccionado previamente
$('#form-reprogramar').submit(function()
{
    event.preventDefault();
    $.ajax({
        url: apiCita + 'reschedule',
        type: 'post',
        data: $('#form-reprogramar').serialize(),
        datatype: 'json'
    })
    .done(function(response){
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
    .fail(function(jqXHR){
        // Se muestran en consola los posibles errores de la solicitud AJAX
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    });
})