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
    fntupdateNivel(resultNivel);
    generarPlanificiacionAut("Edit", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  }




});



$(document).ready(function () {

  $("#btn_siguienteAut").click(function () {
    //var fecIni = document.querySelector("#dtp_fecha_desde").value;
    //var fecFin = document.querySelector("#dtp_fecha_hasta").value;
    //console.log(fecIni+' '+fecFin+' '+fechaDia)    
    generarPlanificiacionAut("Next", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  });
  $("#btn_anteriorAut").click(function () {
    generarPlanificiacionAut("Back", nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin);
  });

  $("#btn_reservar").click(function () {
    //console.log("Reservar");
    reservarUsuario('Create');
  });

  

  $('#cmb_nivel').change(function () {        
    if ($('#cmb_nivel').val() != 0) {        
        fntLLenarNivel();
    } else {
        $('#cmb_NumeroNivel option').remove();
        swal("Error", "Selecione Libro o Nivel" , "error");
    }
});



  $("#txt_NumeroContrato").autocomplete({
    source: function (request, response) {
      let link = base_url + '/Beneficiario/beneficiarioContratoNombres';
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
              rowResult.label = objeto.NumeroContrato + " " + objeto.Nombre + " " + objeto.Apellido;
              rowResult.value = objeto.NumeroContrato;

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
      $('#txt_CodigoBeneficiario').val(ui.item.Cedula);
      //$('#txt_EdadBeneficirio').val(ui.item.Edad);
      //$('#txt_TelefonoBeneficirio').val(ui.item.Telefono);
      $('#txth_idBenef').val(ui.item.id);

    }
  });


  $("#txt_CodigoBeneficiario").autocomplete({
    source: function (request, response) {
      let link = base_url + '/Beneficiario/beneficiarioContratoNombres';
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
              rowResult.Nombres = objeto.Nombre + " " + objeto.Apellido;
              rowResult.NumeroContrato = objeto.NumeroContrato;
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
      $('#txt_NumeroContrato').val(ui.item.NumeroContrato);
      $('#txth_idBenef').val(ui.item.id);
      //$('#txt_EdadBeneficirio').val(ui.item.Edad);
      //$('#txt_TelefonoBeneficirio').val(ui.item.Telefono);
      //$('#txth_per_idBenef').val(ui.item.id);

    }
  });

});

function buscarNivel(ids) {
  if (sessionStorage.dts_Nivel) {
    var Grid = JSON.parse(sessionStorage.dts_Nivel);
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


function fntLLenarNivel() {  
  let objNivel=buscarNivel($('#cmb_nivel').val());
  $("#cmb_NumeroNivel").empty();
  for (var i = objNivel["Uinicio"]; i <= objNivel["Ufin"]; i++) {
    // Crea una opción con el valor y texto igual al número del contador
    var option = $("<option>", {
      value: i,
      text: "Unidad "+ i
    });

    // Agrega la opción al select usando jQuery
    $("#cmb_NumeroNivel").append(option);
  }
}





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

function fntupdateNivel(resultNivel) {
  var c = 0;
  var arrayList = new Array();
  for (var i = 0; i < resultNivel.length; i++) {
    let rowInst = new Object();
    rowInst.ids = resultNivel[i].Ids;
    rowInst.Nombre = resultNivel[i].Nombre;
    rowInst.Uinicio = resultNivel[i].UnidadInicio;
    rowInst.Ufin = resultNivel[i].UnidadFin;
    rowInst.Examen1 = resultNivel[i].Examen1;
    rowInst.Examen2 = resultNivel[i].Examen2;
    arrayList[c] = rowInst;
    c += 1;
  }
  sessionStorage.dts_Nivel = JSON.stringify(arrayList);
}


function generarPlanificiacionAut(accionMove, nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaIni,fechaFin) {
  var tabla = document.getElementById("dts_PlanificiacionAut");
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
      $("#txth_fechaReservacion").val(fechaDia);
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

function buscarInstructor(ids) {
  if (sessionStorage.dts_PlaInstructor) {
    var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
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
  $('#txth_idsModal').val(comp.id);
  DataArray = comp.id.split("_");
  $('#txth_diaLetra').val(DataArray[0]);
  var nDiaLetra = retornarDiaLetras(DataArray[0]);
  var Hora = DataArray[1] + ":00";
  $('#txth_hora').val(DataArray[1]);
  let objInstructor = buscarInstructor(DataArray[2]);
  let objSalon = buscarSalonColor(DataArray[3]);
  console.log(objSalon);
  $('#txth_salon').val(DataArray[3]);
  $('#txt_color').val(objSalon["Color"]);
  $('#lbl_Beneficiario').text($('#txt_NombreBeneficirio').val());
  $('#txth_idsInstru').val(objInstructor["ids"]);  

  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
  document.querySelector('#btn_reservar').classList.replace("btn-info", "btn-primary");
  document.querySelector('#btnText').innerHTML = "Reservar";
  document.querySelector('#titleModal').innerHTML = "Día: " + nDiaLetra + " ->  Hora: " + Hora + " -> Salón: " + objSalon["Nombre"] + " -> Instructor: " + objInstructor["Nombre"];
  document.querySelector("#formAgenda").reset();
  $('#modalFormAgenda').modal('show');
}


function reservarUsuario(accion) {
  let idsModal=$("#txth_idsModal").val();
  let pla_id= $("#txth_ids").val();
  let ben_id= $("#txth_idBenef").val();
  let ins_id= $("#txth_idsInstru").val();
  let act_id = $("#cmb_actividad").val();
  let niv_id = $("#cmb_nivel").val();
  let uni_id = $("#cmb_NumeroNivel").val();
  let sal_id = $("#txth_salon").val();
  let hora = $("#txth_hora").val();
  let diaLetra = $("#txth_diaLetra").val();

  

  //let fechaInicio = $("#dtp_fecha_desde").val();
  //let fechaFin = $("#dtp_fecha_hasta").val();

  /*if (fechaInicio == "" || fechaFin == "" || centroAT == 0) {
    swal(
      "Atención",
      "Todos los Fecha inicio,fecha fin, y Centro de Atención son obligatorios.",
      "error"
    );
    return false;
  }*/
  if (niv_id!=0) {
    let objEnt = new Object();
    objEnt.idsModal = idsModal;
    objEnt.pla_id = pla_id;
    objEnt.ben_id = ben_id;
    objEnt.act_id = act_id;
    objEnt.niv_id = niv_id;
    objEnt.uni_id = uni_id;
    objEnt.ins_id = ins_id;
    objEnt.hora = hora;
    objEnt.sal_id = sal_id;
    objEnt.diaLetra=diaLetra;
    objEnt.fechaReserv=retonarFecha(fechaDia)
    objEnt.fechaInicio = fechaIni;
    objEnt.fechaFin = fechaFin;
    objEnt.centro = CentroIds;
      let link = base_url + "/Reservacion/reservarBeneficiario";
      $.ajax({
        type: "POST",
        url: link,
        data: {
          reservar: objEnt,
          accion: accion,
        },
        success: function (data) {
          if (data.status) {
            controlReservacion(objEnt);
            
            /*sessionStorage.removeItem("dts_PlaInstructor");
            sessionStorage.removeItem("dts_PlaTemporal");
            sessionStorage.removeItem("dts_SalonCentro");
            swal("Planificación", data.msg, "success");
            window.location = base_url + "/planificacion"; //Retorna al Portal Principal*/
            limpiarTextReservacion();
            swal("Planificación", data.msg, "success");
            $('#modalFormPersona').modal("hide");//Oculta el Modal
          } else {
            swal("Error", data.msg, "error");
          }
             
        },
        dataType: "json",
      });
  } else {
    swal("Atención!", "Seleccione un Nivel", "error");
  }
}

function limpiarTextReservacion(){
  //$("#txth_ids").val("");
  $("#txth_idBenef").val("");
  $("#txth_idsInstru").val("");
  $("#cmb_actividad").val("");
  $("#cmb_nivel").val(0);
  $("#cmb_NumeroNivel").val(0);
  $("#txth_salon").val("");
  $("#txth_hora").val("");
  $("#txth_diaLetra").val("");
  $("#txt_NumeroContrato").val("");
  $("#txt_CodigoBeneficiario").val("");
  $("#txt_NombreBeneficirio").val("");
}

function controlReservacion(objEnt) {
  var arr_Reser = new Array();
  if (sessionStorage.dts_Reservacion) {
    var arr_Reser = JSON.parse(sessionStorage.dts_Reservacion);
    var size = arr_Reser.length;
    if (size > 0) {
      if (codigoExiste(objEnt.idsModal, 'idsRes', sessionStorage.dts_Reservacion)) {
        arr_Reser[size] = objDataResVar(objEnt);
        sessionStorage.dts_Reservacion = JSON.stringify(arr_Reser);
      }else{
        swal("Atención!", "Reservación ya existe" , "error");
      }
    }else{
      arr_Reser[0] = objDataResIni(objEnt);
      sessionStorage.dts_Reservacion = JSON.stringify(arr_Reser);
    }

  }else{
    arr_Reser[0] = objDataResIni(objEnt);
    sessionStorage.dts_Reservacion = JSON.stringify(arr_Reser);
  }  
  
}

function objDataResIni(objEnt) {
  rowGrid = new Object();
  rowGrid.idsRes =objEnt.idsModal;
  rowGrid.fecha =objEnt.fechaReserv;
  var arr_Benef = new Array();
  arr_Benef[0] = agregarBeneficiario(objEnt);
  rowGrid.beneficiarios =arr_Benef;

  /*var arr_Benef = new Array();
  var size = rowGrid.beneficiarios.length;
  console.log(size);
  if (size.length > 0) {
    if (codigoExiste(objEnt.ids, 'ids', rowGrid.beneficiarios)) {
      arr_Benef[size] = agregarBeneficiario(objEnt);
      rowGrid.beneficiarios =arr_Benef;
    }else{
      swal("Atención!", "Beneficiario ya existe" , "error");
    }
  }else{
    arr_Benef[0] = agregarBeneficiario(objEnt);
    rowGrid.beneficiarios =arr_Benef;
  }*/
  
  return rowGrid;
}

function agregarBeneficiario(objEnt){
  let objBenef = new Object();
  objBenef.ids=objEnt.ben_id;
  return objBenef;
}

function objDataResVar(objEnt) {
  if (sessionStorage.dts_Reservacion) {
    var arr_Reser = JSON.parse(sessionStorage.dts_Reservacion);
    var size = arr_Grid.length;
    if (size > 0) {
      let index=retornarIndexArray(arr_Reser,"idsRes",objEnt.idsModal)
      if(index>-1){        
        console.log(arr_Reser[index]['beneficiarios']);
      }
    }
    //sessionStorage.dts_Reservacion = JSON.stringify(arr_Reser);
    

  }
}







