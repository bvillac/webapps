(function () {
    "use strict";
    var treeviewMenu = $('.app-menu');
    var treeviewMenu2 = $('.app-menu2');

    // Toggle Sidebar
    $('[data-toggle="sidebar"]').click(function (event) {
        event.preventDefault();
        $('.app').toggleClass('sidenav-toggled');
    });

    // Activate sidebar treeview toggle
    $("[data-toggle='treeview']").click(function (event) {
        event.preventDefault();
        if (!$(this).parent().hasClass('is-expanded')) {
            treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
        }
        $(this).parent().toggleClass('is-expanded');
    });

    // Activate sidebar treeview2 toggle
    $("[data-toggle='treeview2']").click(function (event) {
        event.preventDefault();
        if (!$(this).parent().hasClass('is-expanded')) {
            treeviewMenu2.find("[data-toggle='treeview2']").parent().removeClass('is-expanded');
        }
        $(this).parent().toggleClass('is-expanded');
    });

    // Set initial active toggle
    $("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

    // Set initial active toggle
    //$("[data-toggle='treeview2.'].is-expanded").parent().toggleClass('is-expanded');

    //Activate bootstrip tooltips
    $("[data-toggle='tooltip']").tooltip();

})();

/*
 * Valida la Entrada del Enter
 */
function isEnter(e) {
    //retornar verdadereo si presiona Enter
    var key;
    if (window.event) // IE
    {
        key = e.keyCode;
        if (key == 13 || key == 9) {
            return true;
        }
    } else if (e.which) { // Netscape/Firefox/Opera
        key = e.which;
        // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
        //var key = nav4 ? evt.which : evt.keyCode;	
        if (key == 13 || key == 9) {
            return true;
        }
    }
    return false;
}

function TextMayus(e) {
    e.value = e.value.toUpperCase();
}

//Convierte su primer carácter en su equivalente mayúscula.
function MyPrimera(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//Agrega 0 a la Izq Numeros
function addCeros(tam, num) {
    if (num.toString().length <= tam)
        return addCeros(tam, "0" + num)
    else
        return num;
}

function redondea(sVal, nDec) {
    var sepDecimal = ".";
    var n = parseFloat(sVal);
    var s = "0.00";
    if (!isNaN(n)) {
        n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
        s = String(n);
        //s += (s.indexOf(".") == -1? ".": "") + String(Math.pow(10, nDec)).substr(1);
        s += (s.indexOf(sepDecimal) == -1 ? sepDecimal : "") + String(Math.pow(10, nDec)).substr(1);
        //s = s.substr(0, s.indexOf(".") + nDec + 1);
        s = s.substr(0, s.indexOf(sepDecimal) + nDec + 1);
    }
    return s;
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = number * 1;//makes sure `number` is numeric value
    var str = number.toFixed(decimals ? decimals : 0).toString().split('.');
    var parts = [];
    for (var i = str[0].length; i > 0; i -= 3) {
        parts.unshift(str[0].substring(Math.max(0, i - 3), i));
    }
    str[0] = parts.join(thousands_sep ? thousands_sep : ',');
    return str.join(dec_point ? dec_point : '.');
}

function calculoCostos(costo, margen, numDecimal) {
    //Aplica para los decuentos sin tener perdidas
    precio = (costo / ((100 - margen) / 100));
    return number_format(precio, numDecimal, SPD, SPM);
}

function calcularEdad(inputFechaNacimiento) {
    //const inputFechaNacimiento = document.getElementById('fechaNacimiento');
    const fechaNacimiento = new Date(inputFechaNacimiento);
    const fechaActual = new Date();

    // Calcular la diferencia en milisegundos entre las dos fechas
    const diferenciaMilisegundos = fechaActual - fechaNacimiento;

    // Calcular la edad dividiendo la diferencia en milisegundos por la cantidad de milisegundos en un año
    const edad = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24 * 365.25));

    //document.getElementById('resultado').innerText = `La edad actual es: ${edad} años`;
    return edad;
}

//Obtener Dia de la Semana
function obtenerDiaSemana(numero) {
    let dias = [
        'Domingo',
        'Lunes',
        'Martes',
        'Miércoles',
        'Jueves',
        'Viernes',
        'Sábado',
    ];
    return dias[numero];
}

function retornarDiaLetras(nLetIni){
    switch (nLetIni) {
      case "LU":
        nDia = "Lunes"
        break;
      case "MA":
        nDia = "Martes"
        break;
      case "MI":
        nDia = "Miércoles"
        break;
      case "JU":
        nDia = "Jueves"
        break;
      case "VI":
        nDia = "Viernes"
        break;
      case "SA":
        nDia = "Sábado"
        break;
      default:
        nDia = "Domingo"
    }
    return nDia;
  }



//Obtener fecha con Letras
function obtenerFechaConLetras(fechaDia) {
    let meses = [
        "enero", "febrero", "marzo", "abril", "mayo", "junio",
        "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
    ];

    var fecha = new Date(fechaDia);
    var dia = fecha.getUTCDate();
    var numeroDia = fecha.getUTCDay();
    var nombreDia = obtenerDiaSemana(numeroDia);
    var mes = meses[fecha.getUTCMonth()];
    var ano = fecha.getUTCFullYear();
    return `${nombreDia}, ${dia} de ${mes} de ${ano}`;
}

function obtenerFechaString(fecha){
   return fecha.getUTCFullYear()+"-"+ (fecha.getUTCMonth()-1) +"-"+fecha.getUTCDay();
}

//Obtener FORMATO fecha => 2023-11-13
function obtenerFormatoFecha(fechaString){
    console.log(fechaString);
    //console.log(fechaDia.toLocaleString('es-ES', opciones));
    var partesFecha = fechaString.split("-");
    var fechaReal = new Date(partesFecha[0], partesFecha[1] - 1, partesFecha[2]);
    return fechaReal;
}

function estaEnRango(fecha, fechaInicio, fechaFin) {
    //Si ambas condiciones se cumplen retonra Verdadero
    console.log(fecha);
    console.log("Fecha compara "+fecha+ " fecha INI " + fechaInicio + " FECHA FIN "+fechaFin);
    console.log("Fecha compara "+fecha.getTime()+ " fecha INI " + fechaInicio.getTime() + " FECHA FIN "+fechaFin.getTime());
    //return fecha.getTime() >= fechaInicio.getTime() && fecha.getTime() <= fechaFin.getTime();
    //return fecha >= fechaInicio && fecha <= fechaFin;
    //return fechaInicio<fecha && fechaFin >fecha;
    //return fecha > fechaInicio && fecha < fechaFin;
    if (fecha.getTime() == fechaInicio.getTime()) {
        console.log("Es igual inicio");
        return true;
    } else if (fecha.getTime() == fechaFin.getTime()) {
        console.log("Es igual fin");
        return true;
    }else{
        console.log("Es igual otro");
        return fecha.getTime() >= fechaInicio.getTime() && fecha.getTime() <= fechaFin.getTime();
    }
}

function contarFechaDia(accionMove, fecha) {
    console.log("Contar fecha");
    if (accionMove == "Next") {
        fecha.setDate(fecha.getDate() + 1);
    } else if (accionMove == "Back") {
        fecha.setDate(fecha.getDate() - 1);
    }
    return fecha;
}

//Busca si existe un codigo en la lista JSON
function codigoExiste(value, property, lista) {
    if (lista) {
        var array = JSON.parse(lista);
        for (var i = 0; i < array.length; i++) {
            if (array[i][property] == value) {
                return false;
            }
        }
    }
    return true;
}

//RETORNA EL INDEX DE LA LISTA
function retornarIndexArray(array, property, value) {
    var index = -1;
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            index = i;
            return index;
        }
    }
    return index;
}

//REMUEVE EL EL ITEN DE LA LISTA POR UN DI
function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}

