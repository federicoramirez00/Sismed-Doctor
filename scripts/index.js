// Si quiere una introducción sobre la plantilla En blanco, vea la siguiente documentación:
// http://go.microsoft.com/fwlink/?LinkID=397704
// Para depurar código al cargar la página en cordova-simulate o en dispositivos o emuladores Android: inicie la aplicación, establezca puntos de interrupción 
// y ejecute "window.location.reload()" en la Consola de JavaScript.
(function () {
    "use strict";
    //alert("Ingrese un usuario");
    document.getElementById("btnBuscar").addEventListener('click', BuscarUsuario, false);
    document.getElementById("btnProcesar").addEventListener('click', parimparnulo, false);
    document.getElementById("btnP3").addEventListener('click', eleccion, false);
    document.getElementById("btnP4").addEventListener('click', tabla, false);
    document.getElementById("btnP5").addEventListener('mouseover', notas, false);
    document.getElementById("btnP6").addEventListener('mouseover', votos, false);
    document.addEventListener('deviceready', onDeviceReady.bind(this), false);

    function onDeviceReady() {
        // Controlar la pausa de Cordova y reanudar eventos
        document.addEventListener('pause', onPause.bind(this), false);
        document.addEventListener('resume', onResume.bind(this), false);

        // TODO: Cordova se ha cargado. Haga aquí las inicializaciones que necesiten Cordova.
        alert("Ingrese un usuario");
        document.getElementById("btnBuscar").addEventListener('click', BuscarUsuario, false);
    }

    function onPause() {
        // TODO: esta aplicación se ha suspendido. Guarde el estado de la aplicación aquí.
    }

    function onResume() {
        // TODO: esta aplicación se ha reactivado. Restaure el estado de la aplicación aquí.
    }
    function BuscarUsuario() {
        //alert("dentro de funcion");
        var numero1 = document.getElementById("txtNumero1").value;
        var numero2 = document.getElementById("txtNumero2").value;
        var Resultado = parseInt(numero1) + parseInt(numero2);
        alert("El resultado es " + Resultado);
    }
    function parimparnulo() {
        var numero = parseInt(document.getElementById("txtNumero3").value);
        if (numero == 0) {
            alert("El número ingresado es nulo");
        } else if (numero % 2 == 0) {
            
            
            alert("El número es par");
        } else {
            alert("El número es impar");
        }
    }
    function eleccion() {
        var opcion = document.getElementById("opcion").value;

        var num1p3 = parseInt(document.getElementById("txtNum1P3").value);
        var num2p3 = parseInt(document.getElementById("txtNum2P3").value);
        var resultado = 0;
        switch (opcion) {
            case 'Suma':
                resultado = num1p3 + num2p3;
                alert("La suma de los 2 números es: " + resultado);
                break;
            case 'Resta':
                resultado = num1p3 - num2p3;
                alert("La resta de los 2 números es: " + resultado);
                break;
            case 'Multiplicacion':
                resultado = num1p3 * num2p3;
                alert("La multiplicacion de los 2 números es: " + resultado);
                break;
            case 'Division':
                resultado = num1p3 / num2p3;
                alert("La division de los 2 números es: " + resultado);
                break;
            //default:
            //  alert("Seleccione una opcion correcta: ");
        }

    }
    function tabla() {
        var Num = document.getElementById("txtNum1P4").value;
        for (var i = 1; i <= 10; i++) {
            var res = Num * i;
            document.write(Num + " X " + i + " = " + res);
            document.write("<br>");
        }
    }
    function notas() {
        var contador = 0, acumulador = 0, promedio = 0;

        var Num = parseFloat(prompt("Ingrese la siguiente nota o ingrese 0 para salir"));
        while (Num > 0) {
            contador = contador + 1;
            acumulador = Num + acumulador;
            Num = parseFloat(prompt("Ingrese la siguiente nota o ingrese 0 para salir"));
        }
        promedio = acumulador / contador;
        alert("El promedio de las: " + contador + " es: " + promedio);
    }
    var candidatos = new Array (5);
    function votos() {
        var acumulador = 0, candidato = 0;
        //alert("ingresa los valores para tu arreglo");
        for (var contador = 0; contador < 5; contador++) {
            candidatos[contador] = parseInt(prompt("Ingrese el número correspondiente"));
            //   alert("voto ingresado: " + Num);
        }
        for (var contador = 0; contador < 5; contador++) {
            alert("Los datos ingresados son: " + candidatos[contador]);
        }
    }

} )();