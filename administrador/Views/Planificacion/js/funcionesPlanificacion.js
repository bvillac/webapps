//NOTA IMPORTANTE: Los datos de Aula y instructor se guardan en session Store es decir que se mantienen en memoria mientras dure la selsion
//si existe algun cambio en estas tablas los cambios no se reflejna mientras no se destruya la session o ce cierre el navegador
let delayTimer;
let fechaDia = new Date();
let numeroDia = 0;
let tablePlanificacion;
let tablePlanificacionAut;


document.addEventListener("DOMContentLoaded", function () {
  // Aquí puedes colocar el código que deseas ejecutar después de que la página se ha cargado completamente
  // Por ejemplo, puedes llamar a una función o realizar alguna operación
  tablePlanificacion = $("#tablePlanificacion").dataTable({
    aProcessing: true,
    aServerSide: true,
    language: {
      url: cdnTable,
    },
    ajax: {
      url: " " + base_url + "/Planificacion/consultarPlanificacion",
      dataSrc: "",
    },
    columns: [
      { data: "Centro" },
      { data: "FechaIni" },
      { data: "FechaFin" },
      { data: "Rango" },
      { data: "Estado" },
      { data: "options" },
    ],
    columnDefs: [
      { className: "textleft", targets: [0] },
      { className: "textcenter", targets: [1] }, //Agregamos la clase que va a tener la columna
      { className: "textcenter", targets: [2] },
      { className: "textleft", targets: [3] },
      { className: "textcenter", targets: [4] },
      { className: "textright", targets: [5] },
    ],
    dom: "lBfrtip",
    buttons: [],
    resonsieve: "true",
    bDestroy: true,
    iDisplayLength: 10, //Numero Items Retornados
    order: [[1, "desc"]], //Orden por defecto 1 columna
  });



  tablePlanificacionAut = $("#tablePlanificacionAut").dataTable({
    aProcessing: true,
    aServerSide: true,
    language: {
      url: cdnTable,
    },
    ajax: {
      url: " " + base_url + "/Planificacion/consultarPlanificacionAut",
      dataSrc: "",
    },
    columns: [
      { data: "Centro" },
      { data: "FechaIni" },
      { data: "FechaFin" },
      { data: "Rango" },
      { data: "Estado" },
      { data: "options" },
    ],
    columnDefs: [
      { className: "textleft", targets: [0] },
      { className: "textcenter", targets: [1] }, //Agregamos la clase que va a tener la columna
      { className: "textcenter", targets: [2] },
      { className: "textleft", targets: [3] },
      { className: "textcenter", targets: [4] },
      { className: "textright", targets: [5] },
    ],
    dom: "lBfrtip",
    buttons: [],
    resonsieve: "true",
    bDestroy: true,
    iDisplayLength: 10, //Numero Items Retornados
    order: [[1, "desc"]], //Orden por defecto 1 columna
  });





  //FUNCIONES PARA EDICION
  if (typeof accionForm !== "undefined") {
    // La variable existe EDITAR
    fntupdateInstructor(resultInst);
    fntupdateSalones(resultSalon);
    generarPlanificiacionEdit("Edit", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  } else {
    // La variable no existe NUEVO
    //console.log("es nuevo");
  }

  if (typeof accionFormAut !== "undefined") {
    // La variable existe EDITAR
    fntupdateInstructor(resultInst);
    fntupdateSalones(resultSalon);
    generarPlanificiacionAut("Edit", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  } else {
    // La variable no existe NUEVO
    //console.log("es nuevo");
  }


});



$(document).ready(function () {
  $("#cmb_instructor").selectpicker("render");
  //Nueva Orden
  $("#btn_nuevo").click(function () {
    //eliminarStores();
    window.location = base_url + "/Planificacion/nuevo"; //Retorna al Portal Principal
  });

  $("#cmd_retornar").click(function () {
    //eliminarStores();
    window.location = base_url + "/planificiacion"; //Retorna al Portal Principal
  });

  $("#external-events .fc-event").each(function () {
    // store data so the calendar knows to render an event upon drop
    $(this).data("event", {
      title: $.trim($(this).text()), // use the element's text as the event title
      stick: true, // maintain when user navigates (see docs on the renderEvent method)
    });

    // make the event draggable using jQuery UI
    $(this).draggable({
      zIndex: 999,
      revert: true, // will cause the event to go back to its
      revertDuration: 0, //  original position after the drag
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
    generarPlanificiacion("Gen");
  });
  $("#btn_siguiente").click(function () {
    generarPlanificiacion("Next");
  });
  $("#btn_anterior").click(function () {
    generarPlanificiacion("Back");
  });

  $("#btn_saveTemp").click(function () {
    guardarTemp();
  });

  $("#btn_saveAll").click(function () {
    guardarAll("Create");
  });

  $("#btn_saveAllEdit").click(function () {
    guardarAll("Edit");
  });

  $("#btn_siguienteEdit").click(function () {
    generarPlanificiacionEdit("Next", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  });
  $("#btn_anteriorEdit").click(function () {
    generarPlanificiacionEdit("Back", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  });


  //Autorizados
  $("#btn_siguienteAut").click(function () {
    generarPlanificiacionAut("Next", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  });
  $("#btn_anteriorAut").click(function () {
    generarPlanificiacionAut("Back", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  });

  $("#cmd_clonar").click(function () {
    fntSaveClonar();
  });

});

function fntSalones(ids) {
  $("#cmb_Salon").html('<option value="0">SELECCIONAR SALÓN</option>');
  if (ids != 0) {
    let link = base_url + "/Planificacion/bucarSalonCentro";
    $.ajax({
      type: "POST",
      url: link,
      data: {
        Ids: ids,
      },
      success: function (data) {
        if (data.status) {
          $("#cmb_Salon").prop("disabled", false);
          var result = data.data;
          var c = 0;
          var arrayList = new Array();
          for (var i = 0; i < result.length; i++) {
            $("#cmb_Salon").append(
              '<option value="' +
              result[i].Ids +
              '">' +
              result[i].Nombre +
              "</option>"
            );
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
      dataType: "json",
    });
  } else {
    $("#cmb_Salon").prop("disabled", true);
    swal("Información", "Seleccionar un Salón", "info");
  }
}

function fntInstructor(ids) {
  var arrayList = new Array();
  $("#cmb_instructor").html(
    '<option value="0">SELECCIONAR INSTRUCTOR</option>'
  );
  if (ids != 0) {
    let link = base_url + "/Planificacion/bucarInstructorCentro";
    $.ajax({
      type: "POST",
      url: link,
      data: {
        Ids: ids,
      },
      success: function (data) {
        if (data.status) {
          $("#cmb_instructor").prop("disabled", false);
          var result = data.data;
          var c = 0;
          for (var i = 0; i < result.length; i++) {
            $("#cmb_instructor").append(
              '<option value="' +
              result[i].Ids +
              '">' +
              result[i].Nombre +
              "</option>"
            );
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
          $("#cmb_instructor").selectpicker("refresh");
        } else {
          swal("Error", data.msg, "error");
        }
      },
      dataType: "json",
    });
  } else {
    $("#cmb_instructor").prop("disabled", true);
    swal("Información", "Seleccionar un Instructor", "info");
  }
}

function fntHorasInstructor(ids) {
  //$('#contenedor-padre').html('<h5 class="mb-4">Horas</h5>');
  $("#contenedor-padre").html("");
  if (ids != 0) {
    let link = base_url + "/Planificacion/bucarInstructor";
    $.ajax({
      type: "POST",
      url: link,
      data: {
        Ids: ids,
      },
      success: function (data) {
        if (data.status) {
          //Ids
          $("#TituloHoras").html(
            '<h5 class="mb-4">Horas ' + data.data.Nombres + " </h5>"
          );
          let horaInst = data.data.Horas;
          let arrayHoras = horaInst.split(",");
          arrayHoras = arrayHoras.sort();
          for (var i = 0; i < arrayHoras.length; i++) {
            $("#contenedor-padre").append(
              '<div id="' +
              arrayHoras[i] +
              '" class="fc-event">' +
              fntNameHoras(arrayHoras[i]) +
              "</div>&nbsp;"
            );
          }
        } else {
          swal("Error", data.msg, "error");
        }
      },
      dataType: "json",
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
      result = "LUN-" + nHora + ":00";
      break;
    case "MA":
      result = "MAR-" + nHora + ":00";
      break;
    case "MI":
      result = "MIE-" + nHora + ":00";
      break;
    case "JU":
      result = "JUE-" + nHora + ":00";
      break;
    case "VI":
      result = "VIE-" + nHora + ":00";
      break;
    case "SA":
      result = "SÁB-" + nHora + ":00";
      break;
    default:
  }
  return result;
}

/**************** GENERAR PLANIFICACION  ******************/
function generarPlanificiacion(accionMove) {
  var tabla = document.getElementById("dts_Planificiacion");

  if (sessionStorage.dts_PlaInstructor) {
    var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
    if (Grid.length > 0) {
      let fechaIni=$("#dtp_fecha_desde").val();
      let fechaFin=$("#dtp_fecha_hasta").val();
      if (accionMove == "Gen") {
        fechaDia = obtenerFormatoFecha(fechaIni);
      } else {        
        let estadoFecha = estaEnRango(accionMove,fechaDia, obtenerFormatoFecha(fechaIni), obtenerFormatoFecha(fechaFin));
        //console.log(estadoFecha);
        if(estadoFecha.estado=="FUE"){
          fechaDia=estadoFecha.fecha;
          swal("Atención!", "Fechas fuera de Rango", "error");
          return;
        }
        
      }
      var filaEncabezado = $("<tr></tr>");
      $("#FechaDia").html(obtenerFechaConLetras(fechaDia));
      //ENCABEZADO DE PLANIFICAICONR
      filaEncabezado.append($("<th>Horas</th>"));
      for (var i = 0; i < Grid.length; i++) {
        filaEncabezado.append(
          $("<th>" + Grid[i]["Nombre"].substring(0, 15).toUpperCase() + "</th>")
        );
      }
      $("#dts_Planificiacion thead").html("");
      $("#dts_Planificiacion thead").append(filaEncabezado);
      //FIN PLANIFICION
      let nLetIni = $("#FechaDia").html().toUpperCase();
      nLetIni = nLetIni.substring(0, 2);
      nLetIni = nLetIni == "SÁ" ? "SA" : nLetIni; //Se cambia por la Tilde
      numeroHora = 8;
      var tabla = $("#dts_Planificiacion tbody");
      $("#dts_Planificiacion tbody").html("");
      for (var i = 0; i < 14; i++) {
        //GENERA LAS FILAS
        var fila = "<tr><td>" + numeroHora + ":00</td>";
        for (var col = 0; col < Grid.length; col++) {
          //Obtener los Salones
          //Existe el Salon programado para el instructor segun sus horarios
          let nExiste = existeHorario(
            Grid[col]["Horario"],
            nLetIni + numeroHora
          );
          //nLetIni=>inicialDia;numeroHora=>horaDia;Grid[col]['ids']=>Id Instructor
          let idPlan = nLetIni + "_" + numeroHora + "_" + Grid[col]["ids"];
          if (nExiste) {
            let arrayAula = Grid[col]["Salones"].split(",");
            let objSalon = buscarSalonColor(arrayAula[0]);
            idPlan += "_" + objSalon["ids"]; //Agrega el Id del Salon
            fila += "<td>";
            fila +=
              '<button type="button" id="' +
              idPlan +
              '" class="btn ms-auto btn-lg asignado-true" style="color:white;background-color:' +
              objSalon["Color"] +
              '" onclick="fnt_eventoPlanificado(this)">' +
              objSalon["Nombre"] +
              "</button>";
            fila += "</td>";
          } else {
            fila +=
              '<td><button type="button" id="' +
              idPlan +
              '" class="btn ms-auto btn-lg btn-light" onclick="fnt_eventoPlanificado(this)">AGREGAR</button></td>';
          }
        }
        fila += "</tr>";
        tabla.append(fila);
        numeroHora++;
      }
    }
  }
}

function existeHorario(nHorArray, nDiaHora) {
  nHorArray = nHorArray.split(",");
  if (nHorArray.includes(nDiaHora)) {
    return true;
  }
  return false;
}

function buscarSalonColor(ids) {
  if (sessionStorage.dts_SalonCentro) {
    var Grid = JSON.parse(sessionStorage.dts_SalonCentro);
    if (Grid.length > 0) {
      for (var i = 0; i < Grid.length; i++) {
        if (Grid[i]["ids"] == ids) {
          return Grid[i];
        }
      }
    }
  }
  return 0;
}

function fnt_eventoPlanificado(comp) {
  let nEstado = false;
  let textobutton = comp.innerHTML;
  let idSalon = document.querySelector("#cmb_Salon").value;
  if (idSalon != 0) {
    if (textobutton == "AGREGAR") {
      nEstado = true;
      //openModalSalon(comp);
    } else {
      var respuesta = confirm("Esta seguro de Cambiar.");
      if (respuesta) {
        nEstado = true;
      }
    }
  } else {
      //ELIMINAR SALON RESERVADO
      var respuesta = confirm("Seleccionar un Salón / Eliminar la reservacion.");
      if (respuesta) {
        //'" class="btn ms-auto btn-lg btn-light" onclick="fnt_eventoPlanificado(this)">AGREGAR</button></td>';
        $("#" + comp.id).removeClass("asignado-true").addClass("btn-light");
        $("#" + comp.id).removeAttr("style");
        $("#" + comp.id).html("AGREGAR");
      }
      nEstado = false;
      //swal("Información", "Seleccionar un Salón / Eliminar la reservacion.", "info");
      
    
  }

  if (nEstado) {
    //Camia el Salon cuando es True
    let objSalon = buscarSalonColor(idSalon);
    let nButton = $("#" + comp.id);
    nButton.removeClass("btn-light").addClass("asignado-true");
    nButton.css("color", "white");
    nButton.css("background-color", objSalon["Color"]);
    $("#" + comp.id).html(objSalon["Nombre"]);
    let arrayIds = comp.id.split("_");
    let nuevoId = comp.id;
    if (arrayIds.length > 3) {
      nuevoId =
        arrayIds[0] +
        "_" +
        arrayIds[1] +
        "_" +
        arrayIds[2] +
        "_" +
        objSalon["ids"];
    } else {
      nuevoId += "_" + objSalon["ids"];
    }
    $("#" + comp.id).attr("id", nuevoId); //Se Cambia el Id y se Agrega el Salon asignado
  }
}

function openModalSalon(comp) {
  //document.querySelector('#txth_ids').value = "";//IDS oculto hiden
  //document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
  //document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
  //document.querySelector('#btnText').innerHTML = "Guardar";
  //document.querySelector('#titleModal').innerHTML = "Nueva Registro";
  //document.querySelector("#formSalon").reset();
  //document.querySelector("#"+comp.id).classList.replace("btn ms-auto btn-lg btn-light", "btn ms-auto btn-lg asignado-true");
  //$('#modalFormSalon').modal('show');
}

function objDataRow(nLetIni) {
  let selecionados = "";
  $("#dts_Planificiacion .asignado-true").each(function (index, boton) {
    var botonId = $(boton).attr("id");
    selecionados += botonId + ",";
    //console.log('ID del botón:', botonId);
  });
  selecionados = selecionados.slice(0, selecionados.length - 1); //Quitar la ultima coma
  //let nLetIni = $('#FechaDia').html().toUpperCase();
  //nLetIni = nLetIni.substring(0, 2);
  //nLetIni = (nLetIni == "SÁ") ? "SA" : nLetIni;//Se cambia por la Tilde
  let rowGrid = new Object();
  rowGrid.dia = nLetIni;
  rowGrid.horario = selecionados;
  rowGrid.fecha = new Date(fechaDia);
  return rowGrid;
}

function guardarTemp() {
  let nLetIni = $("#FechaDia").html().toUpperCase();
  nLetIni = nLetIni.substring(0, 2);
  nLetIni = nLetIni == "SÁ" ? "SA" : nLetIni; //Se cambia por la Tilde
  var arrayList = new Array();
  if (sessionStorage.dts_PlaTemporal) {
    arrayList = JSON.parse(sessionStorage.dts_PlaTemporal);
    var size = arrayList.length;
    if (size > 0) {
      if (codigoExiste(nLetIni, "dia", sessionStorage.dts_PlaTemporal)) {
        arrayList[size] = objDataRow(nLetIni);
        sessionStorage.dts_PlaTemporal = JSON.stringify(arrayList);
        swal("Información!", "Planificación Temporal Guardada.", "success");
      } else {
        swal(
          {
            title: "Actualizar",
            text: "¿Realmente quiere Modificar Planificación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, Modificar!",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: false,
            closeOnCancel: true,
          },
          function (isConfirm) {
            if (isConfirm) {
              eliminarItemsDia(nLetIni);
              arrayList = JSON.parse(sessionStorage.dts_PlaTemporal);
              arrayList[arrayList.length] = objDataRow(nLetIni);
              sessionStorage.dts_PlaTemporal = JSON.stringify(arrayList);
              swal(
                "Información!",
                "Planificación Temporal Guardada.",
                "success"
              );
            }
          }
        );
      }
    } else {
      arrayList[0] = objDataRow(nLetIni);
      sessionStorage.dts_PlaTemporal = JSON.stringify(arrayList);
      swal("Información!", "Planificación Temporal Guardada.", "success");
    }
  } else {
    arrayList[0] = objDataRow(nLetIni);
    sessionStorage.dts_PlaTemporal = JSON.stringify(arrayList);
    swal("Información!", "Planificación Temporal Guardada.", "success");
  }
}

function eliminarItemsDia(nDia) {
  if (sessionStorage.dts_PlaTemporal) {
    var Grid = JSON.parse(sessionStorage.dts_PlaTemporal);
    if (Grid.length > 0) {
      var array = findAndRemove(Grid, "dia", nDia);
      sessionStorage.dts_PlaTemporal = JSON.stringify(array);
    }
  }
}

//Guardar todo
function guardarAll(accion) {

  let fechaInicio = $("#dtp_fecha_desde").val();
  let fechaFin = $("#dtp_fecha_hasta").val();
  let centroAT = $("#cmb_CentroAtencion").val();
  if (fechaInicio == "" || fechaFin == "" || centroAT == 0) {
    swal(
      "Atención",
      "Todos los Fecha inicio,fecha fin, y Centro de Atención son obligatorios.",
      "error"
    );
    return false;
  }
  if (sessionStorage.dts_PlaTemporal) {
    var Grid = JSON.parse(sessionStorage.dts_PlaTemporal);
    if (Grid.length > 0) {
      let cabPlan = new Object();
      cabPlan.ids = (accion!='Create')?IdsTemp:0;
      cabPlan.fechaInicio = fechaInicio;
      cabPlan.fechaFin = fechaFin;
      cabPlan.centro = centroAT;
      let link = base_url + "/Planificacion/ingresarPlanificacion";
      $.ajax({
        type: "POST",
        url: link,
        data: {
          cabecera: cabPlan,
          detalle: Grid,
          accion: accion,
        },
        success: function (data) {
          if (data.status) {
            sessionStorage.removeItem("dts_PlaInstructor");
            sessionStorage.removeItem("dts_PlaTemporal");
            sessionStorage.removeItem("dts_SalonCentro");
            swal("Planificación", data.msg, "success");
            window.location = base_url + "/planificacion"; //Retorna al Portal Principal
          } else {
            swal("Error", data.msg, "error");
          }
        },
        dataType: "json",
      });
    } else {
      swal("Atención!", "No existe Planificación", "error");
    }
  } else {
    swal("Atención!", "No existe Planificación", "error");
  }
}

//FUNCION DE EDITAR
function fntupdateInstructor(resultInst) {
  var arrayList = new Array();
  var c = 0;
  for (var i = 0; i < resultInst.length; i++) {
    let rowInst = new Object();
    rowInst.ids = resultInst[i].Ids;
    rowInst.Nombre = resultInst[i].Nombre;
    rowInst.Horario = resultInst[i].Horario;
    rowInst.Salones = resultInst[i].Salones;
    arrayList[c] = rowInst;
    c += 1;
  }
  sessionStorage.dts_PlaInstructor = JSON.stringify(arrayList);
}

function fntupdateSalones(resultSalon) {
  var c = 0;
  var arrayList = new Array();
  for (var i = 0; i < resultSalon.length; i++) {
    let rowInst = new Object();
    rowInst.ids = resultSalon[i].Ids;
    rowInst.Nombre = resultSalon[i].Nombre;
    rowInst.Color = resultSalon[i].Color;
    arrayList[c] = rowInst;
    c += 1;
  }
  sessionStorage.dts_SalonCentro = JSON.stringify(arrayList);
}


function generarPlanificiacionEdit(accionMove, nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin) {
  
  var tabla = document.getElementById("dts_Planificiacion");
  var nDia = "";
  let salonArray = 0;
  let idsSalon = 0;
  if (sessionStorage.dts_PlaInstructor) {
    var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
    if (Grid.length > 0) {

      if (accionMove == "Edit") {
        fechaDia = obtenerFormatoFecha(fechaIni);
      } else {
        let estadoFecha = estaEnRango(accionMove,fechaDia, obtenerFormatoFecha(fechaIni), obtenerFormatoFecha(fechaFin));
        //console.log(estadoFecha);
        if(estadoFecha.estado=="FUE"){
          fechaDia=estadoFecha.fecha;
          swal("Atención!", "Fechas fuera de Rango", "error");
          return;
        }
        
      }

   

      var filaEncabezado = $("<tr></tr>");
      $("#FechaDia").html(obtenerFechaConLetras(fechaDia));
      //ENCABEZADO DE PLANIFICACION INSTRUCTOR
      filaEncabezado.append($("<th>Horas</th>"));
      for (var i = 0; i < Grid.length; i++) {
        filaEncabezado.append(
          $("<th>" + Grid[i]["Nombre"].substring(0, 15).toUpperCase() + "</th>")
        );
      }
      $("#dts_Planificiacion thead").html("");
      $("#dts_Planificiacion thead").append(filaEncabezado);
      //FIN PLANIFICION
      let nLetIni = $("#FechaDia").html().toUpperCase();
      nLetIni = nLetIni.substring(0, 2);
      nLetIni = nLetIni == "SÁ" ? "SA" : nLetIni; //Se cambia por la Tilde
      numeroHora = 8;

      switch (nLetIni) {
        case "LU":
          nDia = nLunes.split(",");
          break;
        case "MA":
          nDia = nMartes.split(",");
          break;
        case "MI":
          nDia = nMiercoles.split(",");
          break;
        case "JU":
          nDia = nJueves.split(",");
          break;
        case "VI":
          nDia = nViernes.split(",");
          break;
        case "SA":
          nDia = nSabado.split(",");
          break;
        default:
          nDia=new Array();
      }
      var tabla = $("#dts_Planificiacion tbody");
      $("#dts_Planificiacion tbody").html("");
      for (var i = 0; i < 14; i++) {
        //GENERA LAS FILAS
        var fila = "<tr><td>" + numeroHora + ":00</td>";
        for (var col = 0; col < Grid.length; col++) {
          //nLetIni=>inicialDia;numeroHora=>horaDia;Grid[col]['ids']=>Id Instructor
          let idPlan = nLetIni + "_" + numeroHora + "_" + Grid[col]["ids"];
          let nResArray = existeHorarioEditar(nDia, idPlan);
          let nExiste = false;
          if (nResArray != "0") {
            salonArray = nResArray[0].split("_");
            idsSalon = salonArray[3];
            nExiste = true;
          }

          if (nExiste) {
            let objSalon = buscarSalonColor(idsSalon);
            idPlan += "_" + objSalon["ids"]; //Agrega el Id del Salon
            fila += "<td>";
            fila +=
              '<button type="button" id="' +
              idPlan +
              '" class="btn ms-auto btn-lg asignado-true" style="color:white;background-color:' +
              objSalon["Color"] +
              '" onclick="fnt_eventoPlanificado(this)">' +
              objSalon["Nombre"] +
              "</button>";
            fila += "</td>";
          } else {
            fila +=
              '<td><button type="button" id="' +
              idPlan +
              '" class="btn ms-auto btn-lg btn-light" onclick="fnt_eventoPlanificado(this)">AGREGAR</button></td>';
          }
        }
        fila += "</tr>";
        tabla.append(fila);
        numeroHora++;
      }
    }
  }
}

function existeHorarioEditar(nHorArray, nDiaHora) {
  const resultados = nHorArray.filter(function (element) {
    return element.includes(nDiaHora);
  });

  if (resultados.length > 0) {
    //console.log(`Se encontraron elementos en el array que contienen "${nDiaHora}":`);
    //console.log(resultados);
    return resultados;
  }
  //console.log(`No se encontraron elementos en el array que contengan "${nDiaHora}".`);
  return "0";
}

//Eliminar Planificacion 
function fntDeletePlanificacion(ids) {
  var ids = ids;
  swal({
    title: "Eliminar Registro",
    text: "¿Realmente quiere eliminar el Registro?",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, eliminar!",
    cancelButtonText: "No, cancelar!",
    closeOnConfirm: false,
    closeOnCancel: true
  }, function (isConfirm) {

    if (isConfirm) {
      var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      var ajaxUrl = base_url + '/Planificacion/eliminar';
      var strData = "ids=" + ids;
      request.open("POST", ajaxUrl, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.send(strData);
      request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
          var objData = JSON.parse(request.responseText);
          if (objData.status) {
            swal("Eliminar!", objData.msg, "success");
            tablePlanificacion.api().ajax.reload(function () {

            });
          } else {
            swal("Atención!", objData.msg, "error");
          }
        }
      }
    }

  });
}

function fntSaveClonar(){
    let accion="";
    var dataObj = new Object();
    dataObj.ids = document.querySelector('#txth_ids').value;
    dataObj.CentroAtencionID = $('#cmb_CentroAtencion').val();
    dataObj.fecIni = $("#dtp_fecha_desde").val();
    dataObj.fecFin = $("#dtp_fecha_hasta").val();
    let link = base_url + '/Planificacion/clonar';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "data": JSON.stringify(dataObj),
            "accion": accion
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('cabeceraOrden');
                swal("Clonado!", data.msg, "success");
                tablePlanificacion.api().ajax.reload(function () { });
                $('#modalFormClonar').modal('hide');
                //$('#modalFormClonar').modal('dispose');
            } else {
                swal("Atención!", data.msg, "error");
            }
        },
        dataType: "json"
    });
    
}


//CLONAR PLANIFICACION
function fntClonarPlanificacion(ids) {
  $('#txth_ids').val(ids);
  document.querySelector('#titleModal').innerHTML = "Clonar Planificación";
  $('#modalFormClonar').modal('show');
}


//AUTORIZAR PLANIFICACION
function fntAutorizarPlanificacion(ids) {
  var ids = ids;
  swal({
    title: "Autorizar Planificación",
    text: "¿Realmente quiere Autorizar la Planificación?",
    type: "warning",
    showCancelButton: true,
    confirmButtonText: "Si, Continuar!",
    cancelButtonText: "No, cancelar!",
    closeOnConfirm: false,
    closeOnCancel: true
  }, function (isConfirm) {

    if (isConfirm) {
      var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
      var ajaxUrl = base_url + '/Planificacion/autorizar';
      var strData = "ids=" + ids;
      request.open("POST", ajaxUrl, true);
      request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      request.send(strData);
      request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
          var objData = JSON.parse(request.responseText);
          if (objData.status) {
            swal("Autorizado!", objData.msg, "success");
            window.location = base_url + "/planificacion/autorizado"; //Retorna al Portal Principal
            //tablePlanificacion.api().ajax.reload(function () {        });
          } else {
            swal("Atención!", objData.msg, "error");
          }
        }
      }
    }

  });
}


//AUTORIZADOS

function generarPlanificiacionAut(accionMove, nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin) {
  var tabla = document.getElementById("dts_PlanificiacionAut");
  var nDia = "";
  let salonArray = 0;
  let idsSalon = 0;
  if (sessionStorage.dts_PlaInstructor) {
    var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
    if (Grid.length > 0) {
      /*fechaDia = new Date(fechaDia);      
      if (accion != "") {
        if (accion == "Next") {
          fechaDia.setDate(fechaDia.getDate() + 1);
        } else {
          fechaDia.setDate(fechaDia.getDate() - 1);
        }
      } else {
        fechaDia = $("#dtp_fecha_desde").val();
      }*/

      if (accionMove == "Edit") {
        fechaDia = obtenerFormatoFecha(fechaIni);
      } else {
        let estadoFecha = estaEnRango(accionMove,fechaDia, obtenerFormatoFecha(fechaIni), obtenerFormatoFecha(fechaFin));
        //console.log(estadoFecha);
        if(estadoFecha.estado=="FUE"){
          fechaDia=estadoFecha.fecha;
          swal("Atención!", "Fechas fuera de Rango", "error");
          return;
        }
        
      }

      var filaEncabezado = $("<tr></tr>");
      $("#FechaDia").html(obtenerFechaConLetras(fechaDia));
      //ENCABEZADO DE PLANIFICACION INSTRUCTOR
      filaEncabezado.append($("<th>Horas</th>"));
      for (var i = 0; i < Grid.length; i++) {
        filaEncabezado.append(
          $("<th>" + Grid[i]["Nombre"].substring(0, 15).toUpperCase() + "</th>")
        );
      }
      $("#dts_PlanificiacionAut thead").html("");
      $("#dts_PlanificiacionAut thead").append(filaEncabezado);
      //FIN PLANIFICION
      let nLetIni = $("#FechaDia").html().toUpperCase();
      nLetIni = nLetIni.substring(0, 2);
      nLetIni = nLetIni == "SÁ" ? "SA" : nLetIni; //Se cambia por la Tilde
      numeroHora = 8;

      switch (nLetIni) {
        case "LU":
          nDia = nLunes.split(",");
          break;
        case "MA":
          nDia = nMartes.split(",");
          break;
        case "MI":
          nDia = nMiercoles.split(",");
          break;
        case "JU":
          nDia = nJueves.split(",");
          break;
        case "VI":
          nDia = nViernes.split(",");
          break;
        case "SA":
          nDia = nSabado.split(",");
          break;
        default:
          nDia=new Array();
      }
      var tabla = $("#dts_PlanificiacionAut tbody");
      $("#dts_PlanificiacionAut tbody").html("");
      for (var i = 0; i < 14; i++) {
        //GENERA LAS FILAS
        var fila = "<tr><td>" + numeroHora + ":00</td>";
        for (var col = 0; col < Grid.length; col++) {
          //nLetIni=>inicialDia;numeroHora=>horaDia;Grid[col]['ids']=>Id Instructor
          let idPlan = nLetIni + "_" + numeroHora + "_" + Grid[col]["ids"];
          let nResArray = existeHorarioEditar(nDia, idPlan);
          let nExiste = false;
          if (nResArray != "0") {
            salonArray = nResArray[0].split("_");
            idsSalon = salonArray[3];
            nExiste = true;
          }

          if (nExiste) {
            let objSalon = buscarSalonColor(idsSalon);
            idPlan += "_" + objSalon["ids"]; //Agrega el Id del Salon
            fila += "<td>";
            fila +='<button type="button" id="' + idPlan +'" class="btn ms-auto btn-lg asignado-true" style="color:white;background-color:' +objSalon["Color"] +'" >' + objSalon["Nombre"] + "</button>";
            fila += "</td>";
          } else {
            //fila +='<td><button type="button" id="' +idPlan + '" class="btn ms-auto btn-lg btn-light" onclick="fnt_eventoPlanificado(this)">AGREGAR</button></td>';
            fila +='<td></td>';
          }
        }
        fila += "</tr>";
        tabla.append(fila);
        numeroHora++;
      }
    }
  }
}


