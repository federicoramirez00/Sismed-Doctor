$(document).ready(function()
{
    chartConsultasFecha();
    chartCitasEstadoDoctor();
    chartConsultasMensuales();
})

const apiConsultas = 'http://localhost/php/api/consultas.php?action=';

//Función para la creación del gráfico de las consultas realizadas por mes
function chartConsultasFecha(){
    $.ajax({
        url: apiConsultas + 'estadisticasCitas&idDoctor='+localStorage.getItem('id'),
        type: 'post',
        data: null,
        datatype: 'json'
    })
    .done(function(response){
        if(isJSONString(response)){
            const result = JSON.parse(response);
            if(result.status){
                //declaración del arreglo para el eje X
                let fechas = [];
                //declaración del arreglo para el eje Y
                let cantidad = [];
                result.dataset.forEach(function(row){
                    //parametros de la base de datos que reciben lo arreglos
                    fechas.push(row.Hora+':00');
                    cantidad.push(row.Citas);

                });
                //determina el tipo de gráfico y los párametros que recibe, id del canva, arreglo para el eje X, arreglo para el eje Y
                //lectura del dato, y título del gráfico
                barGraph('chartConsultasFecha', fechas, cantidad, 'Citas', 'Afluencia de citas')
                
            }else{
                $('#chartConsultasFecha').remove();
            }
        }else{
            console.log(response);
        }

    })
    .fail(function(jqXHR){
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);

    });
}
/*
function chartCitasEstadoDoctor(){
    $.ajax({
        url: apiConsultas + 'citasEstadoDoctor',
        type: 'post',
        data: null,
        datatype: 'json'
    })
    .done(function(response){
        if(isJSONString(response)){
            const result = JSON.parse(response);
            console.log(result);
            if(result.status){
                let estado = [];
                let citas = [];
                result.dataset.forEach(function(row){
                    estado.push(row.estado);
                    citas.push(row.Citas);
                });
                $('#chartDesempenoDoctor').attr('hidden',false);
                doughnutGraph('chartCitasDesempenoDoctor', estado, citas, 'Citas', 'Estadísticas de citas por doctor')
            }else{
                sweetAlert(2,result.exception,null);
                $('#chartDesempenoDoctor').attr('hidden',true);
            }
        }else{
            console.log(response);
        }
    
    })
    .fail(function(jqXHR){
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    
    });
}

function chartConsultasMensuales(){
    $.ajax({
        url: apiConsultas + 'consultasMensuales',
        type: 'post',
        data: null,
        datatype: 'json'
    })
    .done(function(response){
        if(isJSONString(response)){
            const result = JSON.parse(response);
            console.log(result);
            if(result.status){
                let fechas = [];
                let consultas = [];
                result.dataset.forEach(function(row){
                    fechas.push(row.Dia+'/'+row.Mes);
                    consultas.push(row.Consultas);
    
                });
                $('#chartConsultas-2').attr('hidden',false);
                lineGraph('chartConsultasMensuales', fechas, consultas, 'Consultas', 'Consultas realizadas de cada mes')
            }else{
                sweetAlert(2,result.exception,null);
                $('#chartConsultas-2').attr('hidden',true);
            }
        }else{
            console.log(response);
        }
    
    })
    .fail(function(jqXHR){
        console.log('Error: ' + jqXHR.status + ' ' + jqXHR.statusText);
    
    });   
}
*/