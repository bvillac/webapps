let tableSalon;

document.addEventListener('DOMContentLoaded', function () {
    tableSalon = $('#tableCuota').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Cuota/consultarCuota",
            "dataSrc": ""
        },
        "columns": [
            { "data": "DNI" },
            { "data": "RazonSolcial" },
            { "data": "FechaContrato" },
            { "data": "Contrato" },
            { "data": "NumeroPagos" },
            { "data": "ValorMensual" },
            { "data": "ValorDebito" },
            { "data": "ValorAbonos" },
            { "data": "Saldo" },
            { "data": "FechaUltPago" },
            { "data": "EstadoCancelado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0] },
            { 'className': "textleft", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textleft", "targets": [3] },
            { 'className': "textcenter", "targets": [4] },
            { 'className': "textcenter", "targets": [5] },
            { 'className': "textcenter", "targets": [6] },
            { 'className': "textcenter", "targets": [7] },
            { 'className': "textcenter", "targets": [8] },
            { 'className': "textcenter", "targets": [9] },
            { 'className': "textcenter", "targets": [10] },
            { 'className': "textcenter", "targets": [11] }
        ],
        'dom': 'lBfrtip',
        'buttons': [
          {
            "extend": "excelHtml5",
            "text": "<i class='fa fa-file-excel'></i> Excel",
            "titleAttr": "Esportar a Excel",
            "title": "REPORTE GENERAL PAGOS",
            "order": [[0, "asc"]],
            "className": "btn btn-success"
          },
          {
            "extend": "pdfHtml5",
            "text": "<i class='fa fa-file-pdf'></i> PDF",
            "titleAttr": "Esportar a PDF",
            "pageSize": "LETTER",
            "title": "REPORTE GENERAL PAGOS",
            "order": [[0, "asc"]],
            "className": "btn btn-secondary"
          }
        ],

        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[1, "desc"]]  //Orden por defecto 1 columna
    });

});


$(document).ready(function () {
    $("#cmd_guardar").click(function () {
        guardarSalon();
    });


});

function fntRegistarPago(ids) {
    //var estaDeshabilitado = $("#ASI_" + ids).prop('disabled');
    swal({
      title: "Registrar Pago",
      text: "¿Esta seguro de registrar?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Si, Registrar!",
      cancelButtonText: "No, cancelar!",
      closeOnConfirm: false,
      closeOnCancel: true
    }, function (isConfirm) {
  
      if (isConfirm) {
        if (ids != 0) {
          let link = base_url + "/Cuota/realizarPago";
          $.ajax({
            type: "POST",
            url: link,
            data: {
              Ids: ids,
            },
            success: function (data) {
              if (data.status) {
                $("#COB_" + ids).prop("disabled", true);
                location.reload();
                swal("Información", data.msg, "info");
  
              } else {
                swal("Error", data.msg, "error");
              }
            },
            dataType: "json",
          });
        } else {
          //$("#cmb_instructor").prop("disabled", true);
          
          swal("Información", "Seleccionar un Horario", "info");
        }
  
      }else{
        //$("#COB_" + ids).prop("checked", false);
      }
  
    });
  
  }
 
