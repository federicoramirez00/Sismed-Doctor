$(document).ready(function () {
    showTable();
})

const apiCita = 'http://localhost/php/api/disponibilidad.php?action=';

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
        url: apiCita + 'readDisponibilidad',
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