

$(document).ready(function () {

  $("#btn_buscar").click(function () {
    buscarReservaciones();;
  });

});

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


  function buscarReservaciones(){
    //var tGrid = 'tableMovimiento';
    //$('#'+ tGrid + ' > tbody').empty();
    let link = base_url + '/Asistencia/asistenciaFechaHora';
    let Centro=($('#cmb_CentroAtencion').val()!=0)?$('#cmb_CentroAtencion').val():0;
    //let PlaID=($('#cmb_CentroAtencion').val()!=0)?$('#cmb_CentroAtencion').val():0;
    let InsId=($('#cmb_instructor').val()!=0)?$('#cmb_instructor').val():0;
    let hora=($('#cmb_hora').val()!=0)?$('#cmb_hora').val():0;
   

    $.ajax({
        type: 'POST',
        url: link,
        data:{
            "catId": Centro,
            "plaId": 1,
            "insId": InsId,
            "hora": hora,            
            "fechaDia": $("#dtp_fecha").val(),
        } ,
        success: function(data){
            let Response=data;
            //let dataRes=Response.data;
            $('#list_tables').append("");
            if(Response.status){ 
              let table=Response.data;
              let c=0;
              var strtable = ""; 
              while (c <= table.length) {                
                strtable += '<h3 class="tile-title">' + table[c]['InsNombre'] + ' + SALON + ACTIVIDAD + NIVEL</h3>';
                strtable += '<table class="table table-hover">';
                strtable += '<thead>';
                strtable += '<tr>';
                strtable += '<th>#</th>';
                strtable += '<th>USUARIO</th>';
                strtable += '<th>ASISTENCÍA</th>';
                strtable += '</tr>';
                strtable += '</thead>';
                let thoras=table[c]['Reservado'];
                strtable += '<tbody>';
                var strFila = "";
                for (var i = 0; c < thoras.length; c++) { 
                  strFila += '<td>' + i+1 + '</td>';  
                  strFila += '<td>' + thoras[i]['BenNombre'] + '</td>';  
                  strFila += '<td>' + thoras[i]['Estado'] + '</td>';  
                  strtable += '<tr>' + strFila + '</tr>';   
                }
                strtable += '</tbody>';
                strtable += '</table>';
                strtable += '<br>';
              }

              $('#list_tables').append(strtable);
            }else{
              console.log("xxx");
              swal("Atención!", Response.msg, "error");
            }


            //if(dataMov.length){  
            /*if(dataMov.length){ 
                $('#lbl_tIngreso').text(redondea(parseInt(data['TOT_ING']), N2decimal));  
                $('#lbl_tEgreso').text(redondea(parseInt(data['TOT_EGR']), N2decimal));  
                $('#lbl_tSaldo').text(redondea(parseInt(data['TOT_ING'])-parseInt(data['TOT_EGR']), N2decimal));  
                for (var c = 0; c < dataMov.length; c++) {                 
                    var strFila = "";            
                    strFila += '<td>' + dataMov[c]['FECHA'] + '</td>';
                    strFila += '<td>' + dataMov[c]['INGRESO'] + '</td>';
                    strFila += '<td>' + dataMov[c]['EGRESO'] + '</td>';
                    strFila += '<td class="textright">' + dataMov[c]['CANTIDAD'] + '</td>';
                    strFila += '<td class="textright">' + dataMov[c]['SALDO'] + '</td>';
                    strFila += '<td>' + dataMov[c]['ESTADO'] + '</td>';
                    strFila += '<td>' + dataMov[c]['REFERENCIA'] + '</td>';                  
                    strFila = '<tr class="odd gradeX">' + strFila + '</tr>';                   
                    $('#' + tGrid ).append(strFila);
                }

            }else{
                swal("Atención!", "No Existen Datos" , "error");
            }*/
        },
        dataType: "json"
    });
}
  