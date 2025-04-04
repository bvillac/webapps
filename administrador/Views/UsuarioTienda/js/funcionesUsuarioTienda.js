let tableTienda;

document.addEventListener('DOMContentLoaded', function () {
    tableTienda = $('#tableTienda').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/UsuarioTienda/consultarUsuarioTienda",
            "dataSrc": ""
        },
        "columns": [
            { "data": "usuario" },
            { "data": "tiendanombre" },
            { "data": "cliente" },
            { "data": "persona" },
            { "data": "rol" },
            { "data": "fecha" },
            { "data": "estado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0] },
            { 'className': "textleft", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textleft", "targets": [3] },
            { 'className': "textleft", "targets": [4] },
            { 'className': "textcenter", "targets": [5] },
            { 'className': "textcenter", "targets": [6] },
            { 'className': "textcenter", "targets": [7] }
        ],
        'dom': 'lBfrtip',
        'buttons': [],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": numPaginado,//Numero Items Retornados
        "order": [[1, orderBy]]  //Orden por defecto 1 columna
    });


    const precioInput = document.getElementById("txt_PrecioProducto");
    const btnAgregar = document.getElementById("btnAgregar");


});


$(document).ready(function () {
    $("#cmd_guardar").click(function () {
        guardarTienda();
    });

    const checkboxes = document.querySelectorAll(".row-check");

    // Asocia el evento "change" a cada checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            // Obtiene el id del checkbox desde el atributo data-id
            const id = this.getAttribute("data-id");
            // Obtiene el estado actual del checkbox (true si está marcado)
            const isChecked = this.checked;

            // Llama a la función y le pasa los parámetros
            almacenarSeleccion(id, isChecked);

            // Para fines de depuración, imprime el contenido de sessionStorage
            console.log("Seleccionados:", sessionStorage.getItem("seleccionados"));
        });
    });

    $('#cmb_tiendas').change(function () {
        if ($('#cmb_tiendas').val() != 0) {
            obtenerCheckTienda();
        } else {
            //$('#txt_numero_horas').val("0");
            swal("Error", "Selecione una Tienda", "error");
        }
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
    $('#cmb_Cliente').val("0");
    $('#txt_nombreTienda').val("");
    $('#txt_telefono').val("");
    $('#txt_direccion').val("");
    $('#txt_contacto').val("");
    $('#txt_lugar').val("");
    $('#txt_diainicio').val("0");
    $('#txt_diafin').val("0");
    $('#txt_cupo').val("0");
    $('#cmb_estado').val("1");
}

function guardarTienda() {
    let accion = ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let Ids = document.querySelector('#txth_ids').value;
    let cliente_id = $('#cmb_Cliente').val();
    let nombreTienda = $('#txt_nombreTienda').val();
    let telefono = $('#txt_telefono').val();
    let direccion = $('#txt_direccion').val();
    let contacto = $('#txt_contacto').val();
    let lugar = $('#txt_lugar').val();
    let diainicio = $('#txt_diainicio').val();
    let diafin = $('#txt_diafin').val();
    let cupo = $('#txt_cupo').val();
    let estado = $('#cmb_estado').val();
    if (cliente_id == '0' || nombreTienda == '' || telefono == '' || direccion == '' || contacto == '' || contacto == '' || lugar == '' 
            || diainicio == '0' || diafin == '0' || cupo == '0') {
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
    dataObj.cliente_id = cliente_id;
    dataObj.nombreTienda = nombreTienda;
    dataObj.telefono = telefono;
    dataObj.direccion = direccion;
    dataObj.contacto = contacto;
    dataObj.lugar = lugar;
    dataObj.diainicio = diainicio;
    dataObj.diafin = diafin;
    dataObj.cupo = cupo;
    dataObj.estado = estado;

    let url = base_url + '/Tienda/ingresarTienda';
		var metodo = 'POST';
		var dataPost = { accion: accion, dataObj: dataObj };
		peticionAjaxSSL(url, metodo, dataPost, function (data) {
			// Manejar el éxito de la solicitud aquí
			if (data.status) {
				swal("Tienda", data.msg, "success");
                window.location = base_url + '/Tienda/tienda';
			} else {
				swal("Atención", data.msg, "error");
			}

		}, function (jqXHR, textStatus, errorThrown) {
			// Manejar el error de la solicitud aquí
			console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
		});
}

//Editar Registro
function editarTienda(ids) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Tienda";
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
                $('#cmb_Cliente').val(objData.data.Cli_Ids);
                //$('#txt_nombreTienda').val(objData.data.RazonSocial);
                $('#txt_nombreTienda').val(objData.data.NombreTienda);
                $('#txt_telefono').val(objData.data.Telefono);
                $('#txt_direccion').val(objData.data.Direccion);
                $('#txt_contacto').val(objData.data.ContactoTienda);
                $('#txt_lugar').val(objData.data.LugarEntrega);
                $('#txt_diainicio').val(objData.data.FecIni);
                $('#txt_diafin').val(objData.data.FecFin);
                $('#txt_cupo').val(objData.data.Cupo);     
                $('#cmb_estado').val(objData.data.Estado == 1 ? "1" : "0");
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
                document.querySelector("#lbl_cliente").innerHTML = objData.data.RazonSocial;
                document.querySelector("#lbl_nombre").innerHTML = objData.data.NombreTienda;
                document.querySelector("#lbl_telefono").innerHTML = objData.data.Telefono;
                document.querySelector("#lbl_direccion").innerHTML = objData.data.Direccion;
                document.querySelector("#lbl_contacto").innerHTML = objData.data.ContactoTienda;
                document.querySelector("#lbl_lugar").innerHTML = objData.data.LugarEntrega;
                document.querySelector("#lbl_diainicio").innerHTML = objData.data.FecIni;
                document.querySelector("#lbl_diafin").innerHTML = objData.data.FecFin;
                document.querySelector("#lbl_cupo").innerHTML = objData.data.Cupo;
                document.querySelector("#lbl_estado").innerHTML = estadoLogico(objData.data.Estado);
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIngreso; 
                $('#modalViewTienda').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}


function obtenerCheckTienda() {
    let idsTienda = $('#cmb_tiendas').val();
    let url = base_url + '/Tienda/retornarTiendaCheck';
    var metodo = 'POST';
    var datos = { ids: idsTienda };
    peticionAjaxSSL(url, metodo, datos, function (data) {
        uncheckAll();
        // Manejar el éxito de la solicitud aquí
        if (data.status) {
            //eliminarClavesSessionStorage('seleccionados');
            sessionStorage.setItem('seleccionados', JSON.stringify(data.data));
            checkPorId(data.data);
        } else {
            swal("Atención", data.msg, "error");
        }

    }, function (jqXHR, textStatus, errorThrown) {
        // Manejar el error de la solicitud aquí
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });


}

function uncheckAll() {
    document.querySelectorAll(".row-check").forEach(cb => {
        cb.checked = false;
        almacenarSeleccion(cb.dataset.id, false);
    });
}
function checkPorId(seleccion) {
    document.querySelectorAll(".row-check").forEach(cb => {
        cb.checked = seleccion.includes(cb.dataset.id);
    });
}


