//NOTA IMPORTANTE: Los datos de Aula y instructor se guardan en session Store es decir que se mantienen en memoria mientras dure la selsion
//si existe algun cambio en estas tablas los cambios no se reflejna mientras no se destruya la session o ce cierre el navegador
let delayTimer;
//let fechaDia = "";//new Date();
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
    //console.log("ingresoa ="+fechaDia);
    fntupdateInstructor(resultInst);
    fntupdateSalones(resultSalon);
    fntupdateNivel(resultNivel);
    fntupdateReservacion(reservacion);
    fntReservacionCount(resultNumRes);
    generarPlanificiacionAut(nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaDia);
  }


});



$(document).ready(function () {

  $("#btn_siguienteAut").click(function () {   
    var parametros = {
      cat_id: CentroIds,
      pla_id: IdsTemp,
      accion: "Next",
      fechaDia: fechaDia
    };
    var url = base_url+'/Reservacion/moverAgenda?'+ new URLSearchParams(parametros).toString();
    // Redirigir a la nueva URL
    window.location.href = url;
  });
  $("#btn_anteriorAut").click(function () {
    var parametros = {
      cat_id: CentroIds,
      pla_id: IdsTemp,
      accion: "Back",
      fechaDia: fechaDia
    };
    var url = base_url+'/Reservacion/moverAgenda?'+ new URLSearchParams(parametros).toString();
    // Redirigir a la nueva URL
    window.location.href = url;
  });

  $("#btn_reservar").click(function () {
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
              rowResult.ContId=objeto.ContId;
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
      //alert(ui.item.ContId);
      openModalPagos(ui.item.ContId);
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
    rowInst.CupoMax = resultSalon[i].CupoMax;
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


function fntupdateReservacion(reservacion) {
  //console.log(reservacion);
  var c = 0;
  var arrayList = new Array();
  for (var i = 0; i < reservacion.length; i++) {
    let rowInst = new Object();
    rowInst.res_id = reservacion[i].res_id;
    rowInst.ids = reservacion[i].Ids;
    rowInst.Nombres = reservacion[i].Nombres;
    rowInst.cat_id = reservacion[i].cat_id;
    rowInst.act_id = reservacion[i].act_id;
    rowInst.niv_id = reservacion[i].niv_id;
    rowInst.ben_id = reservacion[i].ben_id;
    rowInst.sal_id = reservacion[i].sal_id;
    rowInst.pla_id = reservacion[i].pla_id;
    rowInst.ins_id = reservacion[i].ins_id;
    rowInst.res_unidad = reservacion[i].res_unidad;
    rowInst.res_dia = reservacion[i].res_dia;
    rowInst.res_hora = reservacion[i].res_hora;
    rowInst.res_asistencia = reservacion[i].res_asistencia;
    rowInst.FechaRes = reservacion[i].FechaRes;
    arrayList[c] = rowInst;
    c += 1;
  }
  sessionStorage.dts_Reservacion = JSON.stringify(arrayList);
}

function fntReservacionCount(reservacion) {
  //{"Ids":"LU_10_3_10","count":2}
  var c = 0;
  var arrayList = new Array();
  for (var i = 0; i < reservacion.length; i++) {
    let rowInst = new Object();
    rowInst.ids = reservacion[i].Ids;
    rowInst.count = reservacion[i].count;    
    arrayList[c] = rowInst;
    c += 1;
  }
  sessionStorage.dts_ReserCount = JSON.stringify(arrayList);
}





function generarPlanificiacionAut(nLunes, nMartes, nMiercoles, nJueves, nViernes, nSabado, nDomingo,fechaDia) {
  
  var tabla = document.getElementById("dts_PlanificiacionAut");
  var nDia = "";
  let salonArray = 0;
  let idsSalon = 0;
  if (sessionStorage.dts_PlaInstructor) {
    var Grid = JSON.parse(sessionStorage.dts_PlaInstructor);
    if (Grid.length > 0) {
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
        case "DO":
          nDia = nDomingo.split(",");
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
            
            let objCount=buscarCountAlumnos(idPlan);
            let totalRes=(objCount!=0)?objCount["count"]:0;    
            fila += "<td>";
            fila += '<button type="button" id="' + idPlan + '" class="btn ms-auto btn-lg asignado-true" onclick="openModalAgenda(this)" ';
            fila += '    style="color:white;background-color:' + objSalon["Color"] + '" >' + objSalon["Nombre"] + ' <span id="tot_' + idPlan + '" class="badge badge-light">'+ totalRes +'</span></button>';
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

function existeHorarioEditar(nHorArray, nDiaHora) {
  const resultados = nHorArray.filter(function (element) {
    return element.includes(nDiaHora);
  });

  if (resultados.length > 0) {
    return resultados;
  }
  return "0";
}

function buscarCountAlumnos(ids) {
  if (sessionStorage.dts_ReserCount) {
    var Grid = JSON.parse(sessionStorage.dts_ReserCount);
    if (Grid.length > 0) {
      for (var i = 0; i < Grid.length; i++) {
        //console.log(Grid[i]["ids"]+'=='+ids)
        if (Grid[i]["ids"] == ids) {
          return Grid[i];
        }
      }
    }
  }
  return 0;
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

function openModalAgenda(comp) { //

  if($("#txt_CodigoBeneficiario").val() == "" || $("#txt_NombreBeneficirio").val() == "" ){
    swal("Atención!", "Seleccionar un Beneficiarios", "info");
    return;
  }
  
  $('#txth_idsModal').val(comp.id);
  DataArray = comp.id.split("_");
  $('#txth_diaLetra').val(DataArray[0]);
  var nDiaLetra = retornarDiaLetras(DataArray[0]);
  var Hora = DataArray[1] + ":00";
  $('#txth_hora').val(DataArray[1]);
  let objInstructor = buscarInstructor(DataArray[2]);
  let objSalon = buscarSalonColor(DataArray[3]);
  $('#txth_salon').val(DataArray[3]);
  $('#txt_color').val(objSalon["Color"]);
  $('#lbl_Beneficiario').text($('#txt_NombreBeneficirio').val());
  $('#txth_idsInstru').val(objInstructor["ids"]); 
  
  cargarBeneficiarios(comp.id);

  document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
  document.querySelector('#btn_reservar').classList.replace("btn-info", "btn-primary");
  document.querySelector('#btnText').innerHTML = "Reservar";
  document.querySelector('#titleModal').innerHTML = "Día: " + nDiaLetra + " ->  Hora: " + Hora + " -> Salón: " + objSalon["Nombre"] + " -> Instructor: " + objInstructor["Nombre"];
  document.querySelector("#formAgenda").reset();
  $('#modalFormAgenda').modal('show');
}

function cargarBeneficiarios(ids) {
  let option="";
  if (sessionStorage.dts_Reservacion) {
    var Grid = JSON.parse(sessionStorage.dts_Reservacion);
    if (Grid.length > 0) {
      $("#list_beneficiarios").empty();
      for (var i = 0; i < Grid.length; i++) {
        if (Grid[i]["ids"] == ids) {        
                option+='<li class="list-group-item d-flex justify-content-between align-items-center">';
                  option +=Grid[i]["Nombres"];    
                  //option +='<span class="badge badge-primary badge-pill">X</span>';
                  option += ' <a href="#" class="link_delete" onclick="event.preventDefault();anularReservacion(\'' + Grid[i]["res_id"] + '\',\'' +  Grid[i]["ids"] + '\');"><i class="fa fa-trash"></i></a>';
                option +='</li>';
        }
      }
    }
  }
  $("#list_beneficiarios").append(option);
}

function anularReservacion(ResId,HorId){
  swal({
      title: "Cancelar Reservación",
      text: "¿Realmente quiere Cancelar Reservación?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Si, Eliminar!",
      cancelButtonText: "No, cancelar!",
      closeOnConfirm: false,
      closeOnCancel: true
  }, function(isConfirm) {
   
      if (isConfirm) {
          var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
          var ajaxUrl = base_url+'/Reservacion/anularReservacion';
          var strData = "ids="+ResId;
          request.open("POST",ajaxUrl,true);
          request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          request.send(strData);
          request.onreadystatechange = function(){
              if(request.readyState == 4 && request.status == 200){
                  var objData = JSON.parse(request.responseText);
                  if(objData.status){
                      var Grid = JSON.parse(sessionStorage.dts_Reservacion);
                      var array = findAndRemove(Grid, 'res_id', ResId);
                      sessionStorage.dts_Reservacion = JSON.stringify(array);
                      cargarBeneficiarios(HorId);
                      swal("Cancelar!", objData.msg , "success");
                      //$('#modalFormAgenda').modal("hide");//Oculta el Modal
                      location.reload();
                  }else{
                      swal("Atención!", objData.msg , "error");
                  }
              }
          }
      }

  });

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
    objEnt.fechaReserv=fechaDia;//retonarFecha(fechaDia)
    objEnt.fechaInicio = fechaIni;
    objEnt.fechaFin = fechaFin;
    objEnt.cat_id = CentroIds;
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
            limpiarTextReservacion();
            swal("Planificación", data.msg, "success");
            $('#modalFormAgenda').modal("hide");//Oculta el Modal
            location.reload();
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


function openModalPagos(contId) {
  let url = base_url + "/Cuota/consultarPagos";
  var metodo = 'POST';
  var datos = { IdsCont: contId, parametro2: 'valor2' };
  peticionAjax(url, metodo, datos, function(data) {
    // Manejar el éxito de la solicitud aquí
    recargarGridPagos(data.data.movimiento);

  }, function(jqXHR, textStatus, errorThrown) {
    // Manejar el error de la solicitud aquí
    console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
  });
  $('#modalViewPagos').modal('show');
}
//movimiento
function recargarGridPagos(arr_Grid) {
  var tGrid = 'dts_Control';
  $('#' + tGrid + ' tbody').html("");
  if (arr_Grid.length > 0) {    
    for (var i = 0; i < arr_Grid.length; i++) {
      $('#' + tGrid).append(retornaFilaData(i, arr_Grid));
    }
  }

}

function retornaFilaData(c, Grid) {
  var strFila = "";
  let nFechaPago=(Grid[c]['FECHA_PAGO']=="" || Grid[c]['FECHA_PAGO']===null )?"": Grid[c]['FECHA_PAGO'];
  strFila += '<td>' + Grid[c]['NUMERO'] + '</td>';
  strFila += '<td>' + Grid[c]['FECHA_VENCE'] + '</td>';
  strFila += '<td>' + nFechaPago + '</td>';
  strFila += '<td>' + Grid[c]['CREDITO'] + '</td>';
  strFila += '<td>' + Grid[c]['SALDO'] + '</td>';
  strFila += '<td>' + Grid[c]['CANCELADO'] + '</td>';
  strFila = '<tr class="odd gradeX">' + strFila + '</tr>';
  return strFila;
}













