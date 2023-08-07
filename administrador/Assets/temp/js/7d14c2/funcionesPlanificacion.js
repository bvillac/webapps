/*let calendarEl = document.getElementById('calendar');
let frm = document.getElementById('formulario');
let eliminar = document.getElementById('btnEliminar');
let myModal = new bootstrap.Modal(document.getElementById('myModal'));
document.addEventListener('DOMContentLoaded', function () {
    calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'local',
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev next today',
            center: 'title',
            right: 'dayGridMonth timeGridWeek listWeek'
        },
        events: base_url + 'Home/listar',
        editable: true,
        drop: function() {
            // is the "remove after drop" checkbox checked?
            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
                $(this).remove();
            }
        },
        dateClick: function (info) {
            frm.reset();
            eliminar.classList.add('d-none');
            document.getElementById('start').value = info.dateStr;
            document.getElementById('id').value = '';
            document.getElementById('btnAccion').textContent = 'Registrar';
            document.getElementById('titulo').textContent = 'Registrar Evento';
            myModal.show();
        },

        eventClick: function (info) {
            document.getElementById('id').value = info.event.id;
            document.getElementById('title').value = info.event.title;
            document.getElementById('start').value = info.event.startStr;
            document.getElementById('color').value = info.event.backgroundColor;
            document.getElementById('btnAccion').textContent = 'Modificar';
            document.getElementById('titulo').textContent = 'Actualizar Evento';
            eliminar.classList.remove('d-none');
            myModal.show();
        },
        eventDrop: function (info) {
            const start = info.event.startStr;
            const id = info.event.id;
            const url = base_url + 'Home/drag';
            const http = new XMLHttpRequest();
            const formDta = new FormData();
            formDta.append('start', start);
            formDta.append('id', id);
            http.open("POST", url, true);
            http.send(formDta);
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                     Swal.fire(
                         'Avisos?',
                         res.msg,
                         res.tipo
                     )
                    if (res.estado) {
                        myModal.hide();
                        calendar.refetchEvents();
                    }
                }
            }
        }

    });
    calendar.render();
    frm.addEventListener('submit', function (e) {
        e.preventDefault();
        const title = document.getElementById('title').value;
        const start = document.getElementById('start').value;
        if (title == '' || start == '') {
             Swal.fire(
                 'Avisos?',
                 'Todo los campos son obligatorios',
                 'warning'
             )
        } else {
            const url = base_url + 'Home/registrar';
            const http = new XMLHttpRequest();
            http.open("POST", url, true);
            http.send(new FormData(frm));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                    const res = JSON.parse(this.responseText);
                     Swal.fire(
                         'Avisos?',
                         res.msg,
                         res.tipo
                     )
                    if (res.estado) {
                        myModal.hide();
                        calendar.refetchEvents();
                    }
                }
            }
        }
    });
    eliminar.addEventListener('click', function () {
        myModal.hide();
        Swal.fire({
            title: 'Advertencia?',
            text: "Esta seguro de eliminar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = base_url + 'Home/eliminar/' + document.getElementById('id').value;
                const http = new XMLHttpRequest();
                http.open("GET", url, true);
                http.send();
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log(this.responseText);
                        const res = JSON.parse(this.responseText);
                        Swal.fire(
                            'Avisos?',
                            res.msg,
                            res.tipo
                        )
                        if (res.estado) {
                            calendar.refetchEvents();
                        }
                    }
                }
            }
        })
    });
})*/

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl !== null) {//Revisa si existe el calendario
        var calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            timeZone: 'America/Guayaquil',
            //initialView: 'dayGridWeek',
            initialView: 'timeGrid',
            locale: 'es',
            headerToolbar: {
                left: 'prev next today',
                center: 'title',
                right: 'timeGridWeek listWeek dayGridDay'
                //right: 'dayGridMonth timeGridWeek listWeek dayGridDay dayGrid listDay'
            },
            views: {
                dayGridMonth: { // name of view
                    titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
                    // other view-specific options here
                }
            },
            visibleRange: {
                start: '2023-08-07',
                end: '2023-08-13'
            },
            businessHours: {
                // days of week. an array of zero-based day of week integers (0=Sunday)
                daysOfWeek: [1, 2, 3, 4, 5, 6], // Monday - Thursday
                startTime: '08:00', // a start time (10am in this example)
                endTime: '18:00', // an end time (6pm in this example)
            },
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function () {
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            }, dateClick: function (arg) {
                console.log(arg.date.toString()); // use *local* methods on the native Date Object
                // will output something like 'Sat Sep 01 2018 00:00:00 GMT-XX:XX (Eastern Daylight Time)'
            }
        });
        calendar.render();

    }





});

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


});



function fntSalones(ids) {
    $('#cmb_Salon').html('<option value="">SELECCIONAR CENTRO</option>');
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
                    for (var i = 0; i < result.length; i++) {
                        $('#cmb_Salon').append('<option value="' + result[i].Ids + '">' + result[i].Nombre + '</option>');
                    }
                } else {
                    swal("Error", data.msg, "error");
                }
            },
            dataType: "json"
        });
    } else {
        $('#cmb_Salon').prop('disabled', true);
        swal("Información", "Seleccionar un Centro de Atención", "info");
    }

}

function fntHorasInstructor(ids) {
    $('#contenedor-padre').html('<h5 class="mb-4">Horas</h5>');
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
                    $('#contenedor-padre').html('<h5 class="mb-4">Horas ' + data.data.Nombres + ' </h5>');
                    let horaInst=data.data.Horas;
                    let arrayHoras= horaInst.split(",");
                    for (var i = 0; i < arrayHoras.length; i++) {
                        $('#contenedor-padre').append('<div class="fc-event div-ajustable">' + arrayHoras[i] + '</div>');
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



