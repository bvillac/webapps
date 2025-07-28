//var clavePrivate = "EIhyWpvOuPhIBhTKG54dyTJ2HtFc";
const key = CryptoJS.enc.Utf8.parse("2024567890120156"); 
const iv = CryptoJS.enc.Utf8.parse("1234567820123456"); 

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
    // Detecta si la tecla presionada es Enter (key: 'Enter') o Tab (key: 'Tab')
    const key = e.key;

    // Validamos si es Enter o Tab
    if (key === 'Enter' || key === 'Tab') {
        return true;
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
    return num.toString().padStart(tam, '0');
}


function redondea(sVal, nDec) {
    let n = parseFloat(sVal);
    if (isNaN(n)) return "0.00"; // Manejo de valores inválidos

    // Redondear a 'nDec' decimales
    let s = n.toFixed(nDec);

    return s;
}
function formatearDecimal(valInput, decimal = 2) {
    let valor = parseFloat(valInput.value.trim()); // Asegura que no haya espacios en blanco.
    
    // Verifica si el valor es un número positivo
    if (isNaN(valor) || valor < 0) {
        valInput.value = (0).toFixed(decimal); // Si no es válido, establece el valor como 0.00.
    } else {
        valInput.value = valor.toFixed(decimal); // Redondea el valor a la cantidad de decimales especificados.
    }
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


//Obtener FORMATO fecha => 2023-11-13
function obtenerFormatoFecha(fechaString){
    //console.log(fechaString);
    var partesFecha = fechaString.split("-");
    var fechaReal = new Date(partesFecha[0], partesFecha[1] - 1, partesFecha[2]);
    return fechaReal;
}

function retonarFecha(fecha){
    var dia = fecha.getUTCDate();
    var mes =fecha.getUTCMonth()+1;
    var ano = fecha.getUTCFullYear();
    return ano +'-'+ mes + '-' + dia
}

function estaEnRango(Evento,fecha, fechaInicio, fechaFin) {
    let obtResult = new Object();
    //console.log(Evento + " fecha " +fecha + " fecha ini " +fechaInicio + " fecha fin " +fechaFin);
    fecha=contarFechaDia(Evento,fecha);
    if(fecha.getTime() > fechaInicio.getTime() && fecha.getTime() < fechaFin.getTime()){
        //Dentro del Rengo
        obtResult.estado="OK";
        obtResult.fecha=fecha;    
    }  else if (fecha.getTime() == fechaInicio.getTime()) {
        obtResult.estado="INI";
        obtResult.fecha=fechaInicio;
    } else if (fecha.getTime() == fechaFin.getTime()) {
        obtResult.estado="FIN";
        obtResult.fecha=fechaFin;
    } else if (fecha.getTime() < fechaInicio.getTime()) {
        obtResult.estado="FUE";//Fuera de Rango
        obtResult.fecha=fechaInicio;
    } else if (fecha.getTime() > fechaFin.getTime()) {
        obtResult.estado="FUE";
        obtResult.fecha=fechaFin;
    }else{
        obtResult.estado="INI";
        obtResult.fecha=fechaInicio;
        //obtResult.fecha=0;
    }
    return obtResult;

}

function contarFechaDia(accionMove, fecha) {
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

function peticionAjax(url, metodo, datos, exitoCallback, errorCallback) {
    $.ajax({
      url: url,
      method: metodo,
      data: datos,
      dataType: 'json', // Puedes ajustar esto según el tipo de datos que esperas
      success: function(data) {
        if (exitoCallback && typeof exitoCallback === 'function') {
          exitoCallback(data);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (errorCallback && typeof errorCallback === 'function') {
          errorCallback(jqXHR, textStatus, errorThrown);
        }
      }
    });
  }

  function peticionAjaxSSL(url, metodo, datos, exitoCallback, errorCallback) {
    $.ajax({
      url: url,
      method: metodo,
      data: `data=${dataEncript(JSON.stringify(datos))}`,
      dataType: 'json', // Puedes ajustar esto según el tipo de datos que esperas
      success: function(data) {
        if (exitoCallback && typeof exitoCallback === 'function') {
          exitoCallback(data);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if (errorCallback && typeof errorCallback === 'function') {
          errorCallback(jqXHR, textStatus, errorThrown);
        }
      }
    });
  }
  function dataEncript(postData){
	const encrypted = CryptoJS.AES.encrypt(postData, key, { iv: iv }).toString();
	const base64Data = btoa(encrypted);
	return encodeURIComponent(base64Data);
}

//Retronar Estadospert
function estadoLogico(estado) {
    var estadoReg = "";
    switch (estado) {
        case "1":
            estadoReg = '<span class="badge badge-success">Activo</span>';
            break;
        case "2":
            estadoReg = '<span class="badge badge-danger">Inactivo</span>';
            break;
        default:
            //estadoReg = "NULL"
    }
    return estadoReg;
}

function eliminarClavesSessionStorage(...claves) {
    claves.forEach(clave => sessionStorage.removeItem(clave));// Elimina solo las que se pasan por parametros
    claves.forEach(clave => localStorage.removeItem(clave));// Elimina solo las que se pasan por parametros
    //console.log(`Se eliminaron las claves: ${claves.join(", ")}`);
}

function limpiarSessionStorage() {
    sessionStorage.clear(); // Elimina todos los datos almacenados en sessionStorage
    localStorage.clear(); // Elimina todos los datos almacenados en sessionStorage
    //console.log("Sesión limpiada correctamente.");
}


function buscarDataTable(valorBuscado, campo, dataTableSelector) {
    const table = $(dataTableSelector).DataTable();

    // Validación temprana
    if (!table || valorBuscado == null || !campo) {
        console.warn("Parámetros inválidos o tabla no inicializada");
        return null;
    }

    const filas = table.rows().data();

    // Convertimos a array y usamos find para buscar por el campo dinámico
    const fila = filas.toArray().find(row => String(row[campo]) === String(valorBuscado));

    if (fila) {
        return fila; // Retorna toda la fila si se encuentra
    }

    console.warn(`No se encontró una fila con ${campo} = ${valorBuscado}`);
    return null;
}


function mostrarListaPersona() {
    tablePersonaBuscar = $('#tablePersonas').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Persona/getGenPersonabuscar",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Cedula" },
            { "data": "Nombre" },
            { "data": "Apellido" },
            { "data": "options" }

        ],
        "columnDefs": [
            //{ 'className': "textcenter", "targets": [3] },//Agregamos la clase que va a tener la columna
            //{ 'className': "textright", "targets": [4] },
            // { 'className': "textcenter", "targets": [ 5 ] }
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });

}

function openModalGenBuscarPersona() {
    rowTable = "";
    mostrarListaPersona();
    document.querySelector('#titleModal').innerHTML = "Buscar Personas";
    $('#modalViewGenPersona').modal('show');
}

function buscarGenPersonaDni(codigo) {
    const url = base_url + '/Persona/consultarPersonaIdDni';
    const metodo = 'POST';
    const dataPost = { codigo: codigo };
    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        if (data.status) {
            mostrarPersona(data.data);
            $('#modalViewGenPersona').modal('hide');
        } else {
            swal("Atención", data.msg, "error");
        }
    }, function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}




  
