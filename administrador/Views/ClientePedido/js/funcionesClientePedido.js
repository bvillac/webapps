//document.write(`<script src="${base_url}/Assets/js/cedulaRucPass.js"></script>`);//
var tableCliente;
document.addEventListener('DOMContentLoaded', function () {
    tableCliente = $('#tableCliente').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "scrollCollapse": true,
        "scrollY": '50vh',//400px para automatic
        //"scrollX": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/ClientePedido/getClientes",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Cedula" },
            { "data": "Nombre" },
            { "data": "Direccion" },
            { "data": "Correo" },
            { "data": "Telefono" },
            { "data": "Pago" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Esportar a PDF",
                "pageSize":"LETTER",
                "title":"REPORTE DE CLIENTES REGISTRADOS",
                "order":[[0,"asc"]],
                "className": "btn btn-secondary"
            }


        ],
        
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[0, "asc"]]  //Orden por defecto 1 columna
    });




    //INGRESAR NUEVA PERSONA
    if (document.querySelector("#formUsu")) {
        let formUsuario = document.querySelector("#formUsu");
        formUsuario.onsubmit = function (e) {
            e.preventDefault();
            let strIds = document.querySelector('#txth_ids').value;
            let strDni = document.querySelector('#txt_dni').value;
            let strFecNac = document.querySelector('#dtp_fecha_nacimiento').value;
            let strNombre = document.querySelector('#txt_nombre').value;
            let strApellido = document.querySelector('#txt_apellido').value;
            let strTelefono = document.querySelector('#txt_telefono').value;
            let strDireccion = document.querySelector('#txt_direccion').value;
            let strAlias = document.querySelector('#txt_alias').value;
            let strGenero = document.querySelector('#cmb_genero').value;
            let strEmail = document.querySelector('#txt_correo').value;
            let intEstado = document.querySelector('#cmb_estado').value;
            let intTipoRol = 4;//Rol de Usuario por defecto
            let strPassword = npass;//document.querySelector('#txt_Password').value;


            if (strDni == '' || strFecNac == '' || strApellido == '' || strNombre == '' || strEmail == '' || strTelefono == ''
                || intTipoRol == '' || strDireccion == '' || strAlias == '' || strGenero == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }

            if (strPassword.length <= 8) {
                swal("Atención", "La Claves debe tener un mínimo de 8 caracteres.", "error");
                return false;
            }
            if (strPassword.length > 16) {
                swal("Atención", "La clave no puede tener más de 16 caracteres", "error");
                return false;
            }

            //Verificas los elementos conl clase valid para controlar que esten ingresados
            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0; i < elementsValid.length; i++) {
                if (elementsValid[i].classList.contains('is-invalid')) {
                    swal("Atención", "Por favor verifique los campos ingresados (Color Rojo).", "error");
                    return false;
                }
            }

            if (strDni == "" || strFecNac == "" || strApellido == "" || strNombre == "" || strEmail == "" || strTelefono == ""
                || intTipoRol == '' || strDireccion == '' || strAlias == '' || strGenero == '') {
                swal("Por favor", "Ingrese los datos correctos.", "error");
                return false;
            } else {

                var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                var ajaxUrl = base_url + '/Usuarios/setUsuario';
                var formData = new FormData(formUsuario);
                request.open("POST", ajaxUrl, true);
                request.send(formData);
                request.onreadystatechange = function () {
                    if (request.readyState == 4 && request.status == 200) {
                        var objData = JSON.parse(request.responseText);
                        if (objData.status) {                           
                            $('#txth_per_id').val(objData.dato);
                            $('#txt_CodigoPersona').val(strDni);
                            $('#txt_cli_nombre').val(strNombre + ' ' + strApellido);
                            $('#txt_cli_cedula_ruc').val(strDni);
                            $('#txt_cli_razon_social').val(strNombre + ' ' + strApellido);
                            $('#txt_cli_direccion').val(strDireccion);
                            $('#txt_cli_correo').val(strEmail);                           

                            $('#modalFormUsu').modal("hide");
                            formUsuario.reset();
                            swal("Usuarios", objData.msg, "success");
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }


                }

            }
        }
    }









});


window.addEventListener('load', function () {
    //fntRolAsig();
    //fntPago();
}, false);

document.addEventListener('DOMContentLoaded', function () {
    // Verifica si el campo txth_ids tiene un valor antes de continuar
    const txthIds = document.querySelector('#txth_ids');
    if (txthIds && txthIds.value !== "") {
        
        // Validar y asignar el tipo de DNI
        const selectDni = document.getElementById("txt_cli_tipo_dni");
        if (selectDni && typeof tipoDNI !== "undefined") {
            selectDni.selectedIndex = parseInt(tipoDNI) - 1;
        }

        // Validar y asignar el tipo de pago
        const cmbPago = document.querySelector('#cmb_pago');
        if (cmbPago && typeof tipoPago !== "undefined") {
            cmbPago.value = tipoPago;
        }

        // Validar y asignar el estado (Corrigiendo el error en la variable)
        const cmbEstado = document.querySelector('#cmb_estado');
        if (cmbEstado && typeof estado !== "undefined") {
            cmbEstado.value = estado;
        }
    }
});


function openModal() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Cliente";
    document.querySelector("#formCliente").reset();
    $('#modalFormCliente').modal('show');
}


$(document).ready(function () {
    //Nueva Orden
    $("#btn_cliente").click(function () {
        //eliminarStores();
        window.location = base_url + '/clientePedido/nuevo';//Retorna al Portal Principal
    });

    $("#cmd_retornar").click(function () {
        //eliminarStores();
        window.location = base_url + '/clientePedido';//Retorna al Portal Principal
    });

    //Buscar Persona
    /*$("#txtCodigoPersona").keyup(function (e) {
        e.preventDefault();
        let codigo = $(this).val();
        if (codigo.length >= 4 && codigo != "") {
            buscarPersonaDni(codigo);
        }

    });*/

    $("#txt_dni").blur(function () {
        /*let valor = document.querySelector('#txt_dni').value;
        if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }*/
    });




    $("#txt_CodigoPersona").autocomplete({
        source: function (request, response) {
            let link = base_url + '/Persona/buscarAutoPersona';
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
                            rowResult.label = objeto.Nombre + " " + objeto.Apellido;
                            rowResult.value = objeto.Cedula;
                            rowResult.id = objeto.Ids;
                            rowResult.cedula = objeto.Cedula;
                            rowResult.direccion = objeto.Direccion;
                            arrayList[c] = rowResult;
                            c += 1;
                        }
                        //console.log(arrayList);
                        response(arrayList);
                    } else {
                        //response(data.msg);
                        limpiarAutocompletar();
                        swal("Atención!", data.msg, "info");

                    }
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
            $('#txt_CodigoPersona').val(ui.item.cedula);
            $('#txth_per_id').val(ui.item.id);
            $('#txt_cli_nombre').val(ui.item.label);
            $('#txt_cli_cedula_ruc').val(ui.item.cedula);
            $('#txt_cli_razon_social').val(ui.item.label);
            $('#txt_cli_direccion').val(ui.item.direccion);
            //$('#txt_cli_correo').val(strEmail);    
            //console.log(ui);
        }
    });




});



function limpiarAutocompletar() {
    $('#txt_CodigoPersona').val("");
    //$('#txth_ids').val("");
    $('#txth_per_id').val("");
    $('#txt_cli_nombre').val("");
    //$('#lbl_Ruc').text("");  
    $('#txt_cli_cedula_ruc').val("");
    $('#txt_cli_razon_social').val("");
    $('#txt_cli_direccion').val("");

}

function openModalPersona() {
    rowTable = "";
    //document.querySelector('#txth_ids').value ="";
    //$("#txt_dni").prop("readonly",false);
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector("#formUsu").reset();
    $('#modalFormUsu').modal('show');
}



function guardarCliente(accion) {
    //let accion=($('#cmd_guardar').html()=="Guardar")?'Create':'edit';  

    let txth_ids = document.querySelector('#txth_ids').value;
    let txth_per_id = document.querySelector('#txth_per_id').value;
    let txt_codigo = null;//document.querySelector('#txt_codigo').value;
    let txt_cli_tipo_dni = document.querySelector('#txt_cli_tipo_dni').value;
    let txt_cli_cedula_ruc = document.querySelector('#txt_cli_cedula_ruc').value;
    let txt_cli_razon_social = document.querySelector('#txt_cli_razon_social').value;
    let txt_cli_direccion = document.querySelector('#txt_cli_direccion').value;
    let txt_cli_telefono = document.querySelector('#txt_cli_telefono').value;
    let txt_cli_telefono_oficina = document.querySelector('#txt_cli_telefono_oficina').value;
    let txt_cli_correo = document.querySelector('#txt_cli_correo').value;
    let txt_cli_cargo = document.querySelector('#txt_cli_cargo').value;
    let cmb_pago = document.querySelector('#cmb_pago').value; 
    let cmb_estado = document.querySelector('#cmb_estado').value;

    if (txth_per_id == '' || txt_cli_cedula_ruc == '' || txt_cli_razon_social == '' || txt_cli_telefono == '' || txt_cli_direccion == ''
        || cmb_pago == 0  || txt_cli_correo == '' || txt_cli_cargo == '' ) {
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
    dataObj.ids = txth_ids;
    dataObj.per_id = txth_per_id;
    dataObj.codigo = txt_codigo;
    dataObj.cli_tipo_dni = txt_cli_tipo_dni;
    dataObj.cli_cedula_ruc = txt_cli_cedula_ruc;
    dataObj.cli_razon_social = txt_cli_razon_social;
    dataObj.cli_telefono = txt_cli_telefono;
    dataObj.cli_direccion = txt_cli_direccion;
    dataObj.pago = cmb_pago;
    dataObj.cli_correo = txt_cli_correo;

    dataObj.cli_telefono_oficina = txt_cli_telefono_oficina;
    dataObj.cli_referencia_bancaria =""; //txt_cli_referencia_bancaria;
    dataObj.cli_cargo = txt_cli_cargo;
    dataObj.cli_antiguedad =""; //txt_cli_antiguedad;
    dataObj.ocupacion = "";
    dataObj.cli_ingreso_mensual ="";// cli_ingreso_mensual;
    dataObj.estado = cmb_estado;


    $("#cmd_guardar").prop("disabled", true);
    let link = base_url + '/clientePedido/ingresarCliente';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "dataObj": JSON.stringify(dataObj),
            "accion": accion
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('cabeceraOrden');
                $("#cmd_guardar").prop("disabled", false);
                swal("Instructor", data.msg, "success");
                window.location = base_url + '/clientePedido';
            } else {
                swal("Error", data.msg, "error");
                $("#cmd_guardar").prop("disabled", false);
            }
        },
        dataType: "json"
    });
}

function fntDeleteCliente(ids) {
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
            var ajaxUrl = base_url + '/clientePedido/delCliente';
            var strData = "ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableCliente.api().ajax.reload(function () {

                        });
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}


