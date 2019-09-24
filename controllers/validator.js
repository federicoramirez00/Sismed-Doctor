
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
var hour = today.getHours();
var min = today.getMinutes();
var sec = today.getSeconds();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
time = hour+':'+min;
document.getElementById("update_fecha").setAttribute("min", today);
document.getElementById("update_hora").setAttribute("min", time);