//Función para comprobar si una cadena tiene formato JSON
function isJSONString(string)
{
    try {
        if (string != "[]") {
            JSON.parse(string);
            return true;
        } else {
            return false;
        }
    } catch(error) {
        return false;
    }
}

//Función para manejar los mensajes de notificación al usuario
function sweetAlert(type, text, url)
{
    switch (type) {
        case 1:
            title = "Éxito";
            icon = "success";
            break;
        case 2:
            title = "Error";
            icon = "error";
            break;
        case 3:
            title = "Advertencia";
            icon = "warning";
            break;
        case 4:
            title = "Aviso";
            icon = "info";
    }
    if (url) {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: 'Aceptar',
            closeOnClickOutside: false,
            closeOnEsc: false
        })
        .then(function(value){
            console.log(value);
            location.href = url
        });
    } else {
        swal({
            title: title,
            text: text,
            icon: icon,
            button: 'Aceptar',
            closeOnClickOutside: false,
            closeOnEsc: false
        });
    }
}

/*
*   Función para cargar las opciones en un select de formulario.
*
*   Expects: api (origen de los datos a mostrar), id (identificador del select en el formulario) y selected (valor seleccionado).
*
*   Returns: ninguno.
*/
function fillSelect(api, id, selected)
{
    $.ajax({
        url: api,
        type: 'post',
        data: null,
        datatype: 'json'
    })
    .done(function(response){
        // Se verifica si la respuesta de la API es una cadena JSON, sino se muestra el resultado en consola
        if (isJSONString(response)) {
            const result = JSON.parse(response);
            console.log(result);
            // Se comprueba si el resultado es satisfactorio, sino se muestra la excepción
            if (result.status) {
                let content = '';
                if (!selected) {
                    content += '<option value="" disabled selected>Seleccione una opción</option>';
                }
                result.dataset.forEach(function(row){
                    value = Object.values(row)[0];
                    text = Object.values(row)[1];
                    if (value != selected) {
                        content += `<option value="${value}">${text}</option>`;
                    } else {
                        content += `<option value="${value}" selected>${text}</option>`;
                    }
                });
                $('#' + id).html(content);
            } else {
                $('#' + id).html('<option value="">No hay opciones</option>');
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

/*
*   Función para generar un gráfico de barras
*
*   Expects: canvas (identificador de la etiqueta canvas), xAxis (datos para el eje X), yAxis (datos para el eje Y), legend (etiqueta para los datos) y title (título del gráfico).
*
*   Returns: ninguno.
*/
function barGraph(canvas, xAxis, yAxis, legend, title)
{
    let colors = [];
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    }
    const context = $('#' + canvas);
    const chart = new Chart(context, {
        type: 'bar',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors,
                borderColor: '#000000',
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 100
                    }
                }]
            }
        }
    });
}

//función utilizada para gráficos lineales
function lineGraph(canvas, xAxis, yAxis, legend, title)
{
    //arreglo que define los colores del gráfico de manera aleatoria
    let colors = [];
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    }
    const context = $('#' + canvas);
    const chart = new Chart(context, {
        type: 'line',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                //el color de fondo de se define en base al valor que arreglo envía
                backgroundColor: colors,
                //define el color del borde de la línea
                borderColor: '#138CC9',
                //color del borde de los puntos del gráfico
                pointBorderColor: '#000002',
                //grosor del borde en píxeles
                borderWidth: 3,
                //propiedad que determina si el gráfico tendrá color de relleno
                fill: false,
                //curva de tensión de la línea, al establecer 0 se dibujan líneas rectas
                lineTension: 0,
                //radio en píxeles de los puntos del gráfico
                pointRadius: 5,
                //radio en píxeles de los puntos del gráfico al hacer "hover" o poner el mouse sobre determinado punto
                pointHoverRadius: 10,
                //ancho en píxeles del borde de los puntos del gráfico
                pointBorderWidth: 2
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 100,
                    }
                }]
            }
        }
    });
}

function doughnutGraph(canvas, xAxis, yAxis, legend, title)
{
    let colors = [];
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    }
    const context = $('#' + canvas);
    const chart = new Chart(context, {
        type: 'doughnut',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors,
                borderColor: '#000000',
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: true
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
}

function pieGraph(canvas, xAxis, yAxis, legend, title)
{
    let colors = [];
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    }
    const context = $('#' + canvas);
    const chart = new Chart(context, {
        type: 'pie',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors,
                borderColor: '#000000',
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: true
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
}

function horizontalGraph(canvas, xAxis, yAxis, legend, title)
{
    let colors = [];
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    }
    const context = $('#' + canvas);
    const chart = new Chart(context, {
        type: 'horizontalBar',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors,
                borderColor: '#000000',
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
}

function polarAreaGraph(canvas, xAxis, yAxis, legend, title)
{
    let colors = [];
    for (i = 0; i < xAxis.length; i++) {
        colors.push('#' + (Math.random().toString(16)).substring(2, 8));
    }
    const context = $('#' + canvas);
    const chart = new Chart(context, {
        type: 'polarArea',
        data: {
            labels: xAxis,
            datasets: [{
                label: legend,
                data: yAxis,
                backgroundColor: colors,
                borderColor: '#000000',
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: title
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 10
                    }
                }]
            }
        }
    });
}