let tableTienda;

document.addEventListener('DOMContentLoaded', function () {
    tableTienda = $('#tableTienda').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "scrollCollapse": true,
        "scrollY": '50vh',//400px para automatic
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


    $("#txt_buscarUser").autocomplete({
        appendTo: '#modalFormTienda .modal-content',
        source: async function (request, response) {
            try {
                const link = `${base_url}/usuarios/buscarAutoUsuario`;
                const res = await fetch(link, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ parametro: request.term, limit: 10 })
                });
    
                const data = await res.json();
    
                if (data.status) {
                    const arrayList = data.data.map(objeto => ({
                        label: `${objeto.Cedula	} - ${objeto.NombreLargo}`,
                        value: objeto.NombreLargo,
                        UsuarioId:objeto.UsuarioId,
                        id: objeto.Ids,
                    }));
                    response(arrayList);
                } else {
                    //limpiarAutocompletar();
                    swal("Atención!", data.msg, "info");
                }
            } catch (error) {
                console.log("Error en la búsqueda:", error);
                swal("Error!", "No se pudo obtener los datos.", "error");
            }
        },
        minLength: minLengthGeneral,
        select: function (event, ui) {
            $('#txth_art_id').val(ui.item.id);
            $("#txth_UsuId").val(ui.item.UsuarioId);
            //txtPrecio.focus();
        }
    });


});


$(document).ready(function () {

    $('#cmb_Cliente').selectpicker();
    $('#cmb_rol').selectpicker();
    $('#cmb_tienda').selectpicker();

    $("#cmd_guardar").click(function () {
        guardarUsuarioTienda();
    });


    $('#cmb_Cliente').change(function () {
        const $cmbCliente = $('#cmb_Cliente');
        const clienteId = $cmbCliente.val();
        if (clienteId && clienteId !== '0') {
            fetchTiendas(clienteId);
        } else {
            swal('Error', 'Debe seleccionar un cliente', 'error');
            resetTienda();
        }
    });
   


});

function resetTienda() {
    const $cmbTienda  = $('#cmb_tienda');
    $cmbTienda
        .prop('disabled', true)     
        .val([]);
    $cmbTienda.selectpicker('refresh');
    sessionStorage.removeItem('dts_TiendaCliente');
}

function fetchTiendas(idsCliente) {
    
    const $cmbTienda  = $('#cmb_tienda');
    // Mostrar estado “cargando”
    $cmbTienda
        .prop('disabled', true)
        .empty()
        .append('<option value="0">CARGANDO...</option>');

    let url = base_url + '/Tienda/retornarTiendaporCliente';
    var metodo = 'POST';
    var datos = { ids: idsCliente };
    peticionAjaxSSL(url, metodo, datos, function (data) {

        if (data.status) {
            // Poblar combo y construir array para sessionStorage
            const tiendas = data.data;
            $cmbTienda
                .prop('disabled', false)
                .empty()
                .append('<option value="0">SELECCIONAR TIENDA</option>');

            const storeList = tiendas.map(t => {
                $cmbTienda.append(
                    `<option value="${t.Ids}">${t.Nombre}</option>`
                );
                return { ids: t.Ids, Nombre: t.Nombre, Color: t.Color };
            });
            $cmbTienda.selectpicker('refresh');
            sessionStorage.setItem('dts_TiendaCliente', JSON.stringify(storeList));
        } else {
            swal('Error', 'No se pudieron cargar las tiendas', 'error');
            resetTienda();
        }

    }, function (jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown);
        swal('Error', 'No se pudieron cargar las tiendas', 'error');
        resetTienda();
    });


}



function openModal() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#cmd_guardar').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario Tienda";
    document.querySelector("#formTienda").reset();
    $('#modalFormTienda').modal('show');
}



function guardarUsuarioTienda() {
    let accion = ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let Ids = document.querySelector('#txth_ids').value;
    let idCliente = $('#cmb_Cliente').val();
    let idRol = $('#cmb_rol').val();
    let idTienda = $('#cmb_tienda').val();
    let idUsuario = $('#txth_UsuId').val();
    let txtUsuario = $('#txt_buscarUser').val();
 
    if (idCliente == '0' || idRol == '0' || idTienda == '0' || txtUsuario == '') {
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
    dataObj.idCliente = idCliente;
    dataObj.idRol = idRol;
    dataObj.idTienda = idTienda;
    dataObj.idUsuario = idUsuario;

    let url = base_url + '/UsuarioTienda/ingresarUsuarioTienda';
		var metodo = 'POST';
		var dataPost = { accion: accion, dataObj: dataObj };
		peticionAjaxSSL(url, metodo, dataPost, function (data) {
			// Manejar el éxito de la solicitud aquí
			if (data.status) {
				swal("Tienda", data.msg, "success");
                window.location = base_url + '/UsuarioTienda/usuariotienda';
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





