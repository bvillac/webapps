//NOTA IMPORTANTE: Los datos de Aula y instructor se guardan en session Store es decir que se mantienen en memoria mientras dure la selsion
//si existe algun cambio en estas tablas los cambios no se reflejna mientras no se destruya la session o ce cierre el navegador
let delayTimer;
let fechaDia = new Date();
let numeroDia = 0;
let tablePlanificacion;


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
      url: " " + base_url + "/Reservacion/consultarPlanificacion",
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

  if (typeof accionFormAut !== "undefined") {
    // La variable existe EDITAR
    fntupdateInstructor(resultInst);
    fntupdateSalones(resultSalon);
    generarPlanificiacionAut("Edit", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo);
  }




});



$(document).ready(function () {

  $("#btn_siguienteAut").click(function () {
    //var fecIni = document.querySelector("#dtp_fecha_desde").value;
    //var fecFin = document.querySelector("#dtp_fecha_hasta").value;
    //console.log(fecIni+' '+fecFin+' '+fechaDia)    
    generarPlanificiacionAut("Next", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo);
  });
  $("#btn_anteriorAut").click(function () {
    generarPlanificiacionAut("Back", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo);
  });



  $("#txt_CodigoBeneficiario").autocomplete({
    source: function (request, response) {
      let link = base_url + '/Persona/buscarAutoPersona';
      $.ajax({
        type: 'POST',
        url: link,
        dataType: "json",
        data: {
          buscar: request.term
        },
        success: function (data) {
          var arrayList = new Array;
          var c = 0;
          if (data.status) {
            var result = data.data;
            for (var i = 0; i < result.length; i++) {
              var objeto = result[i];
              var rowResult = new Object();
              rowResult.label = objeto.Cedula + " " + objeto.Nombre + " " + objeto.Apellido;
              rowResult.value = objeto.Cedula;

              rowResult.id = objeto.Ids;
              rowResult.Cedula = objeto.Cedula;
              rowResult.Nombres = objeto.Nombre + " " + objeto.Apellido;;
              //rowResult.FechaNacimiento = objeto.FechaNacimiento;
              //rowResult.Telefono = objeto.Telefono;
              //rowResult.Edad = objeto.Edad;
              arrayList[c] = rowResult;
              c += 1;
            }
            response(arrayList);
          } else {
            response(data.msg);
            //limpiarTexbox();
            swal("Atención!", data.msg, "info");

          }
        }
      });
    },
    minLength: minLengthGeneral,
    select: function (event, ui) {
      $('#txt_NombreBeneficirio').val(ui.item.Nombres);
      //$('#txt_EdadBeneficirio').val(ui.item.Edad);
      //$('#txt_TelefonoBeneficirio').val(ui.item.Telefono);
      //$('#txth_per_idBenef').val(ui.item.id);

    }
  });

});

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

function generarPlanificiacionAut(accion, nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo) {
  var tabla = document.getElementById("dts_PlanificiacionAut");
  var nDia = "";
  let salonArray = 0;
  let idsSalon = 0;
  if (sessionStorage.dts_PlaInstructor) {
    var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
    if (Grid.length > 0) {
      fechaDia = new Date(fechaDia);
      var filaEncabezado = $("<tr></tr>");
      if (accion != "") {
        if (accion == "Next") {
          fechaDia.setDate(fechaDia.getDate() + 1);
        } else {
          fechaDia.setDate(fechaDia.getDate() - 1);
        }
      } else {
        fechaDia = $("#dtp_fecha_desde").val();
      }
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
      }
      var tabla = $("#dts_PlanificiacionAut tbody");
      $("#dts_PlanificiacionAut tbody").html("");
      for (var i = 0; i < 13; i++) {
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
            fila += '<button type="button" id="' + idPlan + '" class="btn ms-auto btn-lg asignado-true" onclick="openModalAgenda(this)" style="color:white;background-color:' + objSalon["Color"] + '" >' + objSalon["Nombre"] + " <span class='badge badge-light'>4</span></button>";
            fila += "</td>";
          } else {
            //fila +='<td><button type="button" id="' +idPlan + '" class="btn ms-auto btn-lg btn-light" onclick="fnt_eventoPlanificado(this)">AGREGAR</button></td>';
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

function openModalAgenda(comp) {
  document.querySelector('#txth_ids').value = "";//IDS oculto hiden
  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
  document.querySelector('#cmd_guardar').classList.replace("btn-info", "btn-primary");
  document.querySelector('#btnText').innerHTML = "Guardar";
  document.querySelector('#titleModal').innerHTML = "Nuevo Salón";
  document.querySelector("#formAgenda").reset();
  $('#modalFormAgenda').modal('show');
}

/*function fnt_eventoPlanificado(comp) {
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
    nEstado = false;
    swal("Información", "Seleccionar un Salón", "info");
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
}*/



