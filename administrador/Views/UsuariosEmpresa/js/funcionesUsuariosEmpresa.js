//Integrar la libreria Cedula Ruc
document.write(`<script src="${base_url}/Assets/js/cedulaRucPass.js"></script>`);//
let tableUsuarios;
let rowTable = "";
//let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {

    tableUsuarios = $('#tableUsuarios').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "scrollCollapse": true,
        "scrollY": '60vh',//400px para automatic
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/UsuariosEmpresa/getUsuariosEmpresa",
            "dataSrc": ""
        },
        
        "columns": [
            { "data": "per_cedula" },
            { "data": "Nombres" },
            { "data": "usu_correo" },
            { "data": "Tiendas" },
            { "data": "RolEmpresa" },
            { "data": "Estado" },
            { "data": "options" },
            { "data": "RolId", "visible": false } // Oculta la columna RolId
        ],

        'dom': 'lBfrtip',
        'buttons': [
            /* {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary"
            }, */

            /* {
              "extend": "excelHtml5",
              "text": "<i class='fas fa-file-excel'></i> Excel",
              "titleAttr":"Esportar a Excel",
              "title":"REPORTE DE USUARIOS REGISTRADOS",
              "order":[[0,"asc"]],
              "className": "btn btn-success"
          },*/

            /*   {
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Esportar a PDF",
                "pageSize":"LETTER",
                "title":"REPORTE DE USUARIOS REGISTRADOS",
                "order":[[0,"asc"]],
                "className": "btn btn-secondary"            
            /* {
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Esportar a CSV",
                "className": "btn btn-info"
            } */
        ],

        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": numPaginado,
        "order": [[0, orderBy]]
    });







    //Actualizar Perfil
    if (document.querySelector("#formPerfil")) {
        let formPerfil = document.querySelector("#formPerfil");
        formPerfil.onsubmit = function (e) {
            e.preventDefault();
            let strNombre = document.querySelector('#txt_nombre').value;
            let strApellido = document.querySelector('#txt_apellido').value;
            let intTelefono = document.querySelector('#txt_Telefono').value;
            let strDireccion = document.querySelector('#txt_direccion').value;
            var strAlias = document.querySelector('#txt_alias').value;
            let strPassword = document.querySelector('#txt_Password').value;
            let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;

            if (strApellido == '' || strNombre == '' || intTelefono == '' || strDireccion == '' || strAlias == '') {
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }


            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0; i < elementsValid.length; i++) {
                if (elementsValid[i].classList.contains('is-invalid')) {
                    swal("Atención", "Por favor verifique los campos en rojo.", "error");
                    return false;
                }
            }
            //divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url + '/Usuarios/setPerfil';
            let formData = new FormData(formPerfil);
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState != 4) return;
                if (request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        $('#modalFormPerfil').modal("hide");
                        swal({
                            title: "",
                            text: objData.msg,
                            type: "success",
                            confirmButtonText: "Aceptar",
                            closeOnConfirm: false,
                        }, function (isConfirm) {
                            if (isConfirm) {
                                location.reload();
                            }
                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
                //divLoading.style.display = "none";
                return false;
            }
        }
    }




}, false);




//Se ejecuta en los eventos de Controles
$(document).ready(function () {
    $('#cmb_Cliente').selectpicker();

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

    $("#txt_dni").blur(function () {
        let valor = document.querySelector('#txt_dni').value;
        /*if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }*/
    });

    $("#btn_GuardarEmpresa").click(function () {
        guardarAsignarUsuarioEmpresa();
    });

    $("#btn_guardarTienda").click(function () {
        guardarTiendasUsuario();
    });
    

    $('#mostrar').click(function () {
        //Comprobamos que la cadena NO esté vacía.
        if ($(this).hasClass('mdi-eye') && ($("#txt_Password").val() != "")) {
            $('#txt_Password').removeAttr('type');
            $('#mostrar').addClass('mdi-eye-off').removeClass('mdi-eye');
            $('.pwdtxt').html("Ocultar contraseña");
        }
        else {
            $('#txt_Password').attr('type', 'password');
            $('#mostrar').addClass('mdi-eye').removeClass('mdi-eye-off');
            $('.pwdtxt').html("Mostrar contraseña");
        }
    });

    $('#mostrar2').click(function () {
        //Comprobamos que la cadena NO esté vacía.
        if ($(this).hasClass('mdi-eye') && ($("#txt_Password2").val() != "")) {
            $('#txt_Password2').removeAttr('type');
            $('#mostrar2').addClass('mdi-eye-off').removeClass('mdi-eye');
            $('.pwdtxt2').html("Ocultar contraseña");
        }
        else {
            $('#txt_Password2').attr('type', 'password');
            $('#mostrar2').addClass('mdi-eye').removeClass('mdi-eye-off');
            $('.pwdtxt2').html("Mostrar contraseña");
        }
    });
    
    $("#btn_guardar").click(function () {
        guardarUsuarioEmpresa();
    });

    $("#btn_CanbiarClave").click(function () {
        cambiarClave();
    });

});




//FUNCION PARA VISTA DE REGISTRO
function fntViewUsu(ids) {
    var ids = ids;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Usuarios/getUsuario/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                var estadoReg = objData.data.Estado == 1 ?
                    '<span class="badge badge-success">Activo</span>' :
                    '<span class="badge badge-danger">Inactivo</span>';
                var genero = objData.data.Genero == "M" ? 'Masculino' : 'Femenino';
                document.querySelector("#lbl_dni").innerHTML = objData.data.Dni;
                document.querySelector("#lbl_nombres").innerHTML = objData.data.Nombre + ' ' + objData.data.Apellido;
                document.querySelector("#lbl_telefono").innerHTML = objData.data.Telefono;
                document.querySelector("#lbl_direccion").innerHTML = objData.data.Direccion;
                document.querySelector("#lbl_alias").innerHTML = objData.data.Alias;
                document.querySelector("#lbl_usuario").innerHTML = objData.data.usu_correo;
                document.querySelector("#lbl_genero").innerHTML = genero;
                document.querySelector("#lbl_rol").innerHTML = objData.data.Rol;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng;
                $('#modalViewUsu').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}





function fntEditUsu(ids) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Datos";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btn_guardar').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    const url = base_url + '/UsuariosEmpresa/getUsuarioEmpresa';
    const metodo = 'POST';
    const dataPost = { ids: ids };

    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        if (data.status) {
            //swal("Éxito", data.msg, "success");
            document.querySelector("#txth_ids").value = data.data.Ids;
            document.querySelector("#txth_perids").value = data.data.per_id;
            document.querySelector("#txth_eusuids").value = data.data.eusu_id;
            document.querySelector("#txt_dni").value = data.data.Dni;
            document.querySelector("#dtp_fecha_nacimiento").value = data.data.FechaNac;
            document.querySelector("#txt_nombre").value = data.data.Nombre;
            document.querySelector("#txt_apellido").value = data.data.Apellido;
            document.querySelector("#txt_telefono").value = data.data.Telefono;
            document.querySelector("#txt_direccion").value = data.data.Direccion;
            document.querySelector("#txt_alias").value = data.data.Alias;
            document.querySelector("#txt_correo").value = data.data.usu_correo;
            document.querySelector("#cmb_rol").value = data.data.RolID;
            document.querySelector("#txt_Password").value = data.data.strPassword;
            $('#cmb_rol').selectpicker('render');
            document.querySelector("#cmb_genero").value = data.data.Genero;
            $('#cmb_genero').selectpicker('render');

            if (data.data.Estado == 1) {
                document.querySelector("#cmb_estado").value = 1;
            } else {
                document.querySelector("#cmb_estado").value = 2;
            }
            $("#txt_dni").prop("readonly", true);
            $("#txt_dni").prop("readonly", true);
            document.getElementById("div_usaurio").classList.add("ocultaElemento");
            document.getElementById("div_rol").classList.add("ocultaElemento");
            $('#cmb_estado').selectpicker('render');
            $('#modalFormUsu').modal('show');
        } else {
            swal("Atención", data.msg, "error");
        }
    }, function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}

function fntDelUsu(ids) {
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
            const url = base_url + '/UsuariosEmpresa/eliminarUsuario';
            const metodo = 'POST';
            const dataPost = { ids: ids };
            peticionAjaxSSL(url, metodo, dataPost, function (data) {
                if (data.status) {
                    swal("Eliminar!", data.msg, "success");
                        tableUsuarios.api().ajax.reload(function () {
                        });                    
                } else {
                    swal("Atención", data.msg, "error");
                }
            }, function (jqXHR, textStatus, errorThrown) {
                console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
            });
        }

    });

}


function openModal() {
    rowTable = "";
    document.querySelector('#txth_ids').value = "";
    $("#txt_dni").prop("readonly", false);
    document.getElementById("div_usaurio").classList.remove("ocultaElemento");
    document.getElementById("div_rol").classList.remove("ocultaElemento");
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btn_guardar').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector("#formUsu").reset();
    $('#modalFormUsu').modal('show');
}

function openModalPerfil() {
    $('#modalFormPerfil').modal('show');
}


function guardarAsignarUsuarioEmpresa() {
    let accion = 'Create'; //($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let valores = $('#selectedValues').val();
    let usuIds = $('#txth_usu_id').val();
    if (usuIds == '0' || valores == '') {
        swal("Atención", "Debe Seleccionar almenos una empresa.", "error");
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
    dataObj.usuIds = usuIds;
    dataObj.valores = valores;

    let url = base_url + '/Empresa/ingresarUsuarioEmpresa';
    var metodo = 'POST';
    var dataPost = { accion: accion, dataObj: dataObj };
    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        // Manejar el éxito de la solicitud aquí
        if (data.status) {
            swal("Tienda", data.msg, "success");
            //window.location = base_url + '/Tienda/tienda';
        } else {
            swal("Atención", data.msg, "error");
        }

    }, function (jqXHR, textStatus, errorThrown) {
        // Manejar el error de la solicitud aquí
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}


function guardarUsuarioEmpresa() {
    const accion = ($('#btnText').html() === "Guardar") ? 'Create' : 'Edit';

    const strIds = document.querySelector('#txth_ids').value.trim();
    const strDni = document.querySelector('#txt_dni').value.trim();
    const strFecNac = document.querySelector('#dtp_fecha_nacimiento').value.trim();
    const strNombre = document.querySelector('#txt_nombre').value.trim();
    const strApellido = document.querySelector('#txt_apellido').value.trim();
    const strTelefono = document.querySelector('#txt_telefono').value.trim();
    const strDireccion = document.querySelector('#txt_direccion').value.trim();
    const strAlias = document.querySelector('#txt_alias').value.trim();
    const strGenero = document.querySelector('#cmb_genero').value.trim();
    const strEmail = document.querySelector('#txt_correo').value.trim();
    const intEstado = document.querySelector('#cmb_estado').value.trim();
    const intTipoRol = document.querySelector('#cmb_rol').value.trim();
    const strPassword = document.querySelector('#txt_Password').value;

    if (accion == "Create") {
        // Validar campos obligatorios
        if ([strDni, strFecNac, strNombre, strApellido, strTelefono, strDireccion, strAlias, strGenero, strEmail, intTipoRol].includes('')) {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return;
        }

        // Validar longitud de la contraseña
        if (strPassword.length < 8 || strPassword.length > 16) {
            swal("Atención", "La clave debe tener entre 8 y 16 caracteres.", "error");
            return;
        }
    }


    // Validar campos con clase .valid
    const elementsValid = document.getElementsByClassName("valid");
    for (let i = 0; i < elementsValid.length; i++) {
        if (elementsValid[i].classList.contains('is-invalid')) {
            swal("Atención", "Por favor verifique los campos ingresados (resaltados en rojo).", "error");
            return;
        }
    }

    // Construir objeto de datos
    const dataObj = {
        usuIds: strIds,
        dni: strDni,
        fecha_nacimiento: strFecNac,
        nombre: strNombre,
        apellido: strApellido,
        telefono: strTelefono,
        direccion: strDireccion,
        alias: strAlias,
        genero: strGenero,
        email: strEmail,
        estado: intEstado,
        rol: intTipoRol,
        password: strPassword
    };

    const url = base_url + '/UsuariosEmpresa/guardarUsuarioEmpresa';
    const metodo = 'POST';
    const dataPost = { accion: accion, dataObj: dataObj };

    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        if (data.status) {
            swal("Éxito", data.msg, "success");
            tableUsuarios.api().ajax.reload();
            // Puedes redireccionar o refrescar lista si deseas
        } else {
            swal("Atención", data.msg, "error");
        }
    }, function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}


function fntEditClave(ids) {
    document.querySelector('#titleModal').innerHTML = "Cambiar Clave";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btn_CanbiarClave').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Cambiar";

    const url = base_url + '/UsuariosEmpresa/getUsuarioEmpresa';
    const metodo = 'POST';
    const dataPost = { ids: ids };

    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        if (data.status) {
            document.querySelector("#txth_ids").value = data.data.Ids;
            document.querySelector("#lbl_correo").innerHTML = data.data.usu_correo;
            $('#txt_Password2').val("");
            $('#modalClave').modal('show');
        } else {
            swal("Atención", data.msg, "error");
        }
    }, function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}


function cambiarClave() {
    let ids = $('#txth_ids').val();
    let strPassword = $('#txt_Password2').val();
    if (strPassword.length < 8 || strPassword.length > 16) {
        swal("Atención", "La clave debe tener entre 8 y 16 caracteres.", "error");
        return;
    }
    const url = base_url + '/UsuariosEmpresa/cambiarClave';
    const metodo = 'POST';
    const dataPost = { ids: ids,clave:strPassword };

    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        // Manejar el éxito de la solicitud aquí
        if (data.status) {
            swal("Tienda", data.msg, "success");
            //window.location = base_url + '/Tienda/tienda';
        } else {
            swal("Atención", data.msg, "error");
        }

    }, function (jqXHR, textStatus, errorThrown) {
        // Manejar el error de la solicitud aquí
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}


function fntVerTienda(ids,rolId) {
    document.querySelector('#titleModal2').innerHTML = "Tiendas";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btn_CanbiarClave').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Guardar";

    const url = base_url + '/UsuariosEmpresa/getTiendasEmpresa';
    const metodo = 'POST';
    const dataPost = { ids: ids };

    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        if (data.status) {
            document.querySelector("#txth_ids").value = data.data.Ids;
            document.querySelector("#lbl_correo2").innerHTML = data.data.usu_correo;
            document.querySelector("#txth_rol_id").value = rolId;
            $('#modalTiendas').modal('show');
        } else {
            swal("Atención", data.msg, "error");
        }
    }, function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}


function guardarTiendasUsuario() {
    const ids = document.querySelector("#txth_ids").value;
    const rolId = document.querySelector("#txth_rol_id").value;
    const select = document.getElementById('list_tiendas');
    const tiendas = Array.from(select.selectedOptions).map(opt => opt.value);

    if (tiendas.length === 0) {
        swal("Atención", "Debe seleccionar al menos una tienda.", "error");
        return;
    }

    const url = base_url + '/UsuariosEmpresa/guardarUsuarioTiendas';
    const metodo = 'POST';
    const dataPost = { ids: ids, tiendas: tiendas,rolId:rolId };

    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        if (data.status) {
            swal("Éxito", data.msg, "success");
            $('#modalTiendas').modal('hide');
            tableUsuarios.api().ajax.reload(function () {}); 
        } else {
            swal("Atención", data.msg, "error");
        }
    }, function (jqXHR, textStatus, errorThrown) {
        console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });
}


function fetchTiendas(idsCliente) {
    
    const $cmbTienda  = $('#cmb_tienda');
    // Mostrar estado “cargando”
    $cmbTienda
        .prop('disabled', true)
        .empty()
        .append('<option value="0">CARGANDO...</option>');

    let url = base_url + '/Tienda/retornarTiendaCLienteGen';
    var metodo = 'POST';
    var datos = { ids: idsCliente };
    peticionAjaxSSL(url, metodo, datos, function (data) {

        if (data.status) {
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
            //sessionStorage.setItem('dts_TiendaCliente', JSON.stringify(storeList));
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





