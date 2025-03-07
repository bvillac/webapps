let tableTienda;

document.addEventListener('DOMContentLoaded', function () {
    tableTienda = $('#tableTienda').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Tienda/consultarTienda",
            "dataSrc": ""
        },
        "columns": [
            { "data": "NombreTienda" },
            { "data": "Direccion" },
            { "data": "Cupo" },
            { "data": "RazonSocial" },
            { "data": "ContactoTienda" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0] },
            { 'className': "textleft", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textleft", "targets": [3] },
            { 'className': "textleft", "targets": [4] },
            { 'className': "textcenter", "targets": [5] },
            { 'className': "textcenter", "targets": [6] }
        ],
        'dom': 'lBfrtip',
        'buttons': [],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": numPaginado,//Numero Items Retornados
        "order": [[1, orderBy]]  //Orden por defecto 1 columna
    });

});


$(document).ready(function () {
    $("#cmd_guardar").click(function () {
        guardarTienda();
    });


});


function openModal() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#cmd_guardar').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Tienda";
    document.querySelector("#formTienda").reset();
    $('#modalFormTienda').modal('show');
}

function limpiarText() {
    $('#txth_ids').val("");
    $('#cmb_CentroAtencion').val("0");
    $('#txt_nombreTienda').val("");
    $('#txt_cupoMinimo').val("0");
    $('#txt_cupoMaximo').val("0");
    $('#cmb_estado').val("1");
}

function guardarTienda() {
    let accion = ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let Ids = document.querySelector('#txth_ids').value;
    let centro_id = $('#cmb_CentroAtencion').val();
    let nombreTienda = $('#txt_nombreTienda').val();
    let cupominimo = $('#txt_cupoMinimo').val();
    let cupomaximo = $('#txt_cupoMaximo').val();
    let color = $('#txt_color').val();
    let estado = $('#cmb_estado').val();
    if (centro_id == '0' || nombreTienda == '' || cupominimo == '0' || cupomaximo == '0') {
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
    dataObj.nombre = nombreTienda;
    dataObj.cupominimo = cupominimo;
    dataObj.cupomaximo = cupomaximo;
    dataObj.color = color;
    dataObj.estado = estado;
    //sessionStorage.dataInstructor = JSON.stringify(dataInstructor);
    let link = base_url + '/Tienda/ingresarTienda';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "tienda": JSON.stringify(dataObj),
            "accion": accion
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('cabeceraOrden');
                swal("Beneficiarios", data.msg, "success");
                window.location = base_url + '/Tienda/tienda';
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });
}

//Editar Registro
function editarTienda(ids) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Salón";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#cmd_guardar').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Tienda/consultarTiendaId/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                $('#txth_ids').val(objData.data.Ids);
                $('#cmb_CentroAtencion').val(objData.data.cat_id);
                $('#txt_nombreTienda').val(objData.data.NombreTienda);
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
        $('#modalFormTienda').modal('show');
    }
}

function fntDeleteTienda(ids) {
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
            var ajaxUrl = base_url + '/Tienda/eliminarTienda';
            var strData = "ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableTienda.api().ajax.reload(function () {

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
function fntViewTienda(ids){
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Tienda/consultarTiendaId/'+ids;
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
                document.querySelector("#lbl_nombre").innerHTML = objData.data.NombreTienda;
                document.querySelector("#lbl_cupominimo").innerHTML = objData.data.CupoMinimo;
                document.querySelector("#lbl_cupomaximo").innerHTML = objData.data.CupoMaximo;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIngreso; 
                $('#modalViewTienda').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}


