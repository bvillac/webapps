let tableSalon;

document.addEventListener('DOMContentLoaded', function () {
    tableSalon = $('#tableControl').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Academico/consultarControl",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Contrato" },
            { "data": "Nombres" },
            { "data": "FechaIngreso" },
            { "data": "Tipo" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0] },
            { 'className': "textleft", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textleft", "targets": [3] },
            { 'className': "textcenter", "targets": [4] },
            { 'className': "textcenter", "targets": [5] }
        ],
        'dom': 'lBfrtip',
        'buttons': [],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[1, "desc"]]  //Orden por defecto 1 columna
    });

});


$(document).ready(function () {
    $("#cmd_guardar").click(function () {
        guardarEvaluacion();
    });

    $("#cmb_valoracion").change(function() {
        var valorSeleccionado = $(this).val();
        //console.log("Seleccionaste: " + valorSeleccionado);
        if(valorSeleccionado==5){
            $("#cmb_porcentaje").prop("disabled", false);//Habilita
        }else{
            $("#cmb_porcentaje").prop("disabled", true);//Deshabilita
        }
    });


});


function evaluarModals(Ids) {
    document.querySelector('#txth_idsControl').value = Ids;//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#cmd_guardar').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Registrar Evaluación";
    document.querySelector("#formEvaluar").reset();
    $('#modalFormEvaluar').modal('show');
}

function limpiarText() {
    $('#txth_ids').val("");
    $('#cmb_CentroAtencion').val("0");
    $('#txt_nombreSalon').val("");
    $('#txt_cupoMinimo').val("0");
    $('#txt_cupoMaximo').val("0");
    $('#cmb_estado').val("1");
}

function guardarEvaluacion() {
    let Ids = document.querySelector('#txth_idsControl').value;
    let val_id = $('#cmb_valoracion').val();
    let val_por = $('#cmb_porcentaje').val();
    let comentario = $('#txta_comentario').val();
    
    //if (centro_id == '0' || nombresalon == '' || cupominimo == '0' || cupomaximo == '0') {
    //    swal("Atención", "Todos los campos son obligatorios.", "error");
    //    return false;
    //}

    var dataObj = new Object();
    dataObj.ids = Ids;
    dataObj.val_id = val_id;
    dataObj.val_por = val_por;
    dataObj.comentario = comentario;
    let link = base_url + '/Academico/ingresarEvaluacion';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "data": JSON.stringify(dataObj),
            "accion": "Evaluar"
        },
        success: function (data) {
            if (data.status) {
                swal("Evaluación", data.msg, "success");
                location.reload();
                //window.location = base_url + '/Salon/salon';
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });
}


