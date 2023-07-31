let tableBeneficiario;

document.addEventListener('DOMContentLoaded', function () {
    tableBeneficiario = $('#tableBeneficiario').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Beneficiario/consultarBeneficiario",
            "dataSrc": ""
        },
        "columns": [
            { "data": "NumeroContrato" },
            { "data": "TipoBenefiario" },
            { "data": "Nombres" },
            { "data": "Telefono" },
            { "data": "Direccion" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0] },
            { 'className': "textcenter", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textleft", "targets": [3] },
            { 'className': "textleft", "targets": [4] },
            { 'className': "textcenter", "targets": [5] },
            { 'textcenter': "textcenter", "targets": [6] }
        ],
        'dom': 'lBfrtip',
        'buttons': [
            /* {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary"
            }, */

            /*{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr": "Esportar a Excel",
                "title": "REPORTE DE USUARIOS REGISTRADOS",
                "order": [[0, "asc"]],
                "className": "btn btn-success"
            },*/


        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[1, "desc"]]  //Orden por defecto 1 columna
    });

});


$(document).ready(function () {

    $("#cmd_retornar").click(function () {
        //eliminarStores();
        window.location = base_url + '/Beneficiario/beneficiario';//Retorna al Portal Principal
    });

});

function guardarBeneficiario(accion) {
    //let accion=($('#cmd_guardar').html()=="Guardar")?'Create':'edit';    
    let Ids = document.querySelector('#txth_ids').value;
    let centro_id = $('#cmb_CentroAtencion').val();
    let paquete_id = $('#cmb_PaqueteEstudios').val();
    let modalidad_id = $('#cmb_ModalidadEstudios').val();
    let idioma_id = $('#cmb_Idioma').val();
    let numero_meses = $('#txt_numero_meses').val();
    let numero_horas = $('#txt_numero_horas').val();
    let tiular=($('#chk_tipoBeneficiario').prop('checked'))?1:0;
    let examen=($('#chk_ExamenInter').prop('checked'))?1:0;
    
    if (Ids == '' || centro_id == '0' || paquete_id == '0' || modalidad_id == '0' || idioma_id == '0') {
        swal("Atención", "Todos los campos son obligatorios.", "error");
        return false;
    }
    var dataBenef = new Object();
    dataBenef.ids = Ids;
    dataBenef.CentroAtencionID = centro_id;
    dataBenef.PaqueteEstudiosID = paquete_id;
    dataBenef.ModalidadEstudiosID = modalidad_id;
    dataBenef.IdiomaID = idioma_id;
    dataBenef.NMeses = numero_meses;
    dataBenef.NHoras = numero_horas;
    dataBenef.Observaciones = "";
    dataBenef.tiular = tiular;
    dataBenef.ExaInternacional = examen;

    //sessionStorage.dataInstructor = JSON.stringify(dataInstructor);

    let link = base_url + '/Beneficiario/ingresarBeneficiario';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "beneficiario": JSON.stringify(dataBenef),
            "accion": accion
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('cabeceraOrden');
                swal("Beneficiarios", data.msg, "success");
                window.location = base_url + '/Beneficiario/beneficiario';
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });
}





function fntDeleteBeneficiario(ids) {
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
            var ajaxUrl = base_url + '/Beneficiario/eliminarBeneficiario';
            var strData = "ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableInstructor.api().ajax.reload(function () {

                        });
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}

