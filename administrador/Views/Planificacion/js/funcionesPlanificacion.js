let delayTimer;
let fechaDia = new Date();
let numeroDia =0;
$(document).ready(function () {
    $('#cmb_instructor').selectpicker('render');
    //Nueva Orden
    $("#btn_nuevo").click(function () {
        //eliminarStores();
        window.location = base_url + '/Planificacion/nuevo';//Retorna al Portal Principal
    });

    $("#cmd_retornar").click(function () {
        //eliminarStores();
        window.location = base_url + '/planificiacion';//Retorna al Portal Principal
    });

    $('#external-events .fc-event').each(function () {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
            title: $.trim($(this).text()), // use the element's text as the event title
            stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });

    $("#agregarBoton").click(function () {
        const contenedorPadre = document.getElementById("contenedor-padre");
        const nuevoDiv = document.createElement("div");
        nuevoDiv.textContent = "Nuevo Div Agregado";
        nuevoDiv.classList.add("nuevo-div");
        contenedorPadre.appendChild(nuevoDiv);
    });


    $("#btn_generar").click(function () {
        generarPlanificiacion("");

    });
    $("#btn_siguiente").click(function () {
        generarPlanificiacion("Next");

    });
    $("#btn_anterior").click(function () {
        generarPlanificiacion("Back");

    });


});





function fntSalones(ids) {
    $('#cmb_Salon').html('<option value="">SELECCIONAR SALÓN</option>');
    if (ids != 0) {
        let link = base_url + '/Planificacion/bucarSalonCentro';
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                "Ids": ids
            },
            success: function (data) {
                if (data.status) {
                    $('#cmb_Salon').prop('disabled', false);
                    var result = data.data;
                    var c = 0;
                    var arrayList = new Array;
                    for (var i = 0; i < result.length; i++) {
                        $('#cmb_Salon').append('<option value="' + result[i].Ids + '">' + result[i].Nombre + '</option>');
                        let rowInst = new Object();
                        rowInst.ids = result[i].Ids;
                        rowInst.Nombre = result[i].Nombre;
                        rowInst.Color = result[i].Color;
                        arrayList[c] = rowInst;
                        c += 1;
                    }
                    sessionStorage.dts_SalonCentro = JSON.stringify(arrayList);
                    clearTimeout(delayTimer);
                    delayTimer = setTimeout(function () {
                        fntInstructor(ids);
                    }, 500); // Retardo de 500 ms (medio segundo)

                } else {
                    swal("Error", data.msg, "error");
                }
            },
            dataType: "json"
        });
    } else {
        $('#cmb_Salon').prop('disabled', true);
        swal("Información", "Seleccionar un Salón", "info");
    }

}

function fntInstructor(ids) {
    var arrayList = new Array;
    $('#cmb_instructor').html('<option value="">SELECCIONAR INSTRUCTOR</option>');
    if (ids != 0) {
        let link = base_url + '/Planificacion/bucarInstructorCentro';
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                "Ids": ids
            },
            success: function (data) {
                if (data.status) {
                    $('#cmb_instructor').prop('disabled', false);
                    var result = data.data;
                    var c = 0;
                    for (var i = 0; i < result.length; i++) {
                        $('#cmb_instructor').append('<option value="' + result[i].Ids + '">' + result[i].Nombre + '</option>');
                        let rowInst = new Object();
                        rowInst.ids = result[i].Ids;
                        rowInst.Nombre = result[i].Nombre;
                        rowInst.Horario = result[i].Horario;
                        rowInst.Salones = result[i].Salones;
                        arrayList[c] = rowInst;
                        c += 1;
                    }
                    sessionStorage.dts_PlaInstructor = JSON.stringify(arrayList);
                    //$('#cmb_instructor').selectpicker('render');
                    $('#cmb_instructor').selectpicker('refresh');
                } else {
                    swal("Error", data.msg, "error");
                }
            },
            dataType: "json"
        });
    } else {
        $('#cmb_instructor').prop('disabled', true);
        swal("Información", "Seleccionar un Instructor", "info");
    }

}




function fntHorasInstructor(ids) {
    //$('#contenedor-padre').html('<h5 class="mb-4">Horas</h5>');
    $('#contenedor-padre').html('');
    if (ids != 0) {
        let link = base_url + '/Planificacion/bucarInstructor';
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                "Ids": ids
            },
            success: function (data) {
                if (data.status) {//Ids
                    $('#TituloHoras').html('<h5 class="mb-4">Horas ' + data.data.Nombres + ' </h5>');
                    let horaInst = data.data.Horas;
                    let arrayHoras = horaInst.split(",");
                    arrayHoras = arrayHoras.sort();
                    for (var i = 0; i < arrayHoras.length; i++) {
                        $('#contenedor-padre').append('<div id="' + arrayHoras[i] + '" class="fc-event">' + fntNameHoras(arrayHoras[i]) + '</div>&nbsp;');
                    }
                } else {
                    swal("Error", data.msg, "error");
                }
            },
            dataType: "json"
        });
    } else {
        swal("Información", "Seleccionar un Instructor", "info");


    }

}

function fntNameHoras(str) {
    let nDia = str.substring(0, 2);
    let nHora = str.substring(2, 4);
    let result = "";
    switch (nDia) {
        case "LU":
            //result="LUNES "+nHora+":00";
            result = "LUN-" + nHora + ":00";
            break;
        case "MA":
            //result="MARTES "+nHora+":00";
            result = "MAR-" + nHora + ":00";
            break;
        case "MI":
            //result="MIÉRCOLES "+nHora+":00";
            result = "MIE-" + nHora + ":00";
            break;
        case "JU":
            //result="JUEVES "+nHora+":00";
            result = "JUE-" + nHora + ":00";
            break;
        case "VI":
            //result="VIERNES "+nHora+":00";
            result = "VIE-" + nHora + ":00";
            break;
        case "SA":
            //result="SÁBADO "+nHora+":00";
            result = "SÁB-" + nHora + ":00";
            break;
        default:

    }
    return result;
}


/**************** GENERAR PLANIFICACION  ******************/
function generarPlanificiacion(accion) {
    var tabla = document.getElementById("dts_Planificiacion");

    if (sessionStorage.dts_PlaInstructor) {
        var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
        if (Grid.length > 0) {
            //var encabezado = $("#dts_Planificiacion thead");
            var filaEncabezado = $("<tr></tr>");
            //var fechaDia = new Date($('#dtp_fecha_desde').val());
            if (accion != "") {
                fechaDia = new Date(fechaDia);
                if (accion == "Next") {
                    fechaDia.setDate(fechaDia.getDate() + 1);
                } else {
                    fechaDia.setDate(fechaDia.getDate() - 1);
                }
                console.log("dia semana numero "+numeroDia);
            } else {
                fechaDia = $('#dtp_fecha_desde').val();
            }

            $('#FechaDia').html(obtenerFechaConLetras(fechaDia));

            filaEncabezado.append($("<th>Horas</th>"));
            for (var i = 0; i < Grid.length; i++) {
                filaEncabezado.append($("<th>" + Grid[i]['Nombre'] + "</th>"));
            }
            $("#dts_Planificiacion thead").html("");
            $("#dts_Planificiacion thead").append(filaEncabezado);
            numeroHora = 8;
            var tabla = $('#dts_Planificiacion tbody');
            $("#dts_Planificiacion tbody").html("");
            for (var i = 0; i < 13; i++) {
                var fila = '<tr><td>' + numeroHora + ':00</td>';
                for (var col = 0; col < Grid.length; col++) {
                    let arrayAula = Grid[col]['Salones'].split(",");
                    console.log('BUSCAR ID= '+arrayAula[0]);
                    let objAula=buscarSalonColor(arrayAula[0]);
                    console.log('Resultado =>'+objAula['Nombre']);
                    //fila += '<td>' + Grid[col]['Nombre'] + ':00</td>';
                    fila += '<td>';
                    //fila +=  Grid[col]['Nombre'] ;
                    //fila += '<div class="border ms-auto p-1" style="--bs-bg-opacity: .5;background-color:red" >' + Grid[col]['Nombre'] + '</div>';
                    fila += '<div class="border ms-auto p-1" style="--bs-bg-opacity: .5;background-color:'+objAula['Color']+'" >' + objAula['Nombre'] + '</div>';
                    fila += '</td>';
                }
                fila += '</tr>';
                tabla.append(fila);
                numeroHora++;
            }
        }
    }
}
function obtenerDiaSemana(numero){
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

function obtenerFechaConLetras(fechaDia) {
    let meses = [
        "enero", "febrero", "marzo", "abril", "mayo", "junio",
        "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"
    ];
    
    var fecha = new Date(fechaDia);
    var dia = fecha.getUTCDate();
    numeroDia =fecha.getUTCDay();
    var nombreDia = obtenerDiaSemana(numeroDia);
    var mes = meses[fecha.getUTCMonth()];
    var año = fecha.getUTCFullYear();
    return `${nombreDia}, ${dia} de ${mes} de ${año}`;
}

function buscarSalonColor(ids){
    if (sessionStorage.dts_SalonCentro) {
        var Grid = JSON.parse(sessionStorage.dts_SalonCentro);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {
                if(Grid[i]['ids']==ids){
                    console.log('encontro IDS= '+Grid[i])
                    return Grid[i];
                }
            }
        }

    }
    return 0;
}


