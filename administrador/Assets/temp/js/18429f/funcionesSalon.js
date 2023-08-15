let tableSalon;

document.addEventListener('DOMContentLoaded', function () {
    tableSalon = $('#tableSalon').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Salon/consultarSalon",
            "dataSrc": ""
        },
        "columns": [
            { "data": "NombreCentro" },
            { "data": "NombreSalon" },
            { "data": "CupoMinimo" },
            { "data": "CupoMaximo" },
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
        guardarSalon();
    });


});


function openModal() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#cmd_guardar').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Salón";
    document.querySelector("#formSalon").reset();
    $('#modalFormSalon').modal('show');
}

function limpiarText() {
    $('#txth_ids').val("");
    $('#cmb_CentroAtencion').val("0");
    $('#txt_nombreSalon').val("");
    $('#txt_cupoMinimo').val("0");
    $('#txt_cupoMaximo').val("0");
    $('#cmb_estado').val("1");
}

function guardarSalon() {
    let accion = ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let Ids = document.querySelector('#txth_ids').value;
    let centro_id = $('#cmb_CentroAtencion').val();
    let nombresalon = $('#txt_nombreSalon').val();
    let cupominimo = $('#txt_cupoMinimo').val();
    let cupomaximo = $('#txt_cupoMaximo').val();
    let color = $('#txt_color').val();
    let estado = $('#cmb_estado').val();
    if (centro_id == '0' || nombresalon == '' || cupominimo == '0' || cupomaximo == '0') {
        swal("Atención", "Todos los campos son obligatorios.", "error");
        return false;
    }
    let elementsValid = document.getElementsByClassName("valid");
    for (let i = 0; i < elementsValid.length; i++) {
        if (elementsValid[i].classList.contains('is-invalid')) {
            swal("Atención", "Por favor verifique los campos ingresados (Color Rojo).", "error");
            return false;
        }
    }

    var dataObj = new Object();
    dataObj.ids = Ids;
    dataObj.CentroAtencionID = centro_id;
    dataObj.nombre = nombresalon;
    dataObj.cupominimo = cupominimo;
    dataObj.cupomaximo = cupomaximo;
    dataObj.color = color;
    dataObj.estado = estado;
    //sessionStorage.dataInstructor = JSON.stringify(dataInstructor);
    let link = base_url + '/Salon/ingresarSalon';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "salon": JSON.stringify(dataObj),
            "accion": accion
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('cabeceraOrden');
                swal("Beneficiarios", data.msg, "success");
                window.location = base_url + '/Salon/salon';
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });
}

//Editar Registro
function editarSalon(ids) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Salón";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#cmd_guardar').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Salon/consultarSalonId/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                $('#txth_ids').val(objData.data.Ids);
                $('#cmb_CentroAtencion').val(objData.data.cat_id);
                $('#txt_nombreSalon').val(objData.data.NombreSalon);
                $('#txt_cupoMinimo').val(objData.data.CupoMinimo);
                $('#txt_cupoMaximo').val(objData.data.CupoMaximo); 
                $('#txt_color').val(objData.data.Color);      
                if (objData.data.Estado == 1) {
                    $('#cmb_estado').val("1");
                } else {
                    $('#cmb_estado').val("1");
                }

            }
        }
        $('#modalFormSalon').modal('show');
    }
}

function fntDeleteSalon(ids) {
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
            var ajaxUrl = base_url + '/Salon/eliminarSalon';
            var strData = "ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableSalon.api().ajax.reload(function () {

                        });
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}

//FUNCION PARA VISTA DE REGISTRO
function fntViewSalon(ids){
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Salon/consultarSalonId/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
               var estadoReg = objData.data.Estado == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_centro").innerHTML = objData.data.NombreCentro;
                document.querySelector("#lbl_nombre").innerHTML = objData.data.NombreSalon;
                document.querySelector("#lbl_cupominimo").innerHTML = objData.data.CupoMinimo;
                document.querySelector("#lbl_cupomaximo").innerHTML = objData.data.CupoMaximo;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIngreso; 
                $('#modalViewSalon').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}


