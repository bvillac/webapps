//Integrar la libreria Cedula Ruc
document.write(`<script src="${base_url}/Assets/js/cedulaRucPass.js"></script>`);//
let tableUsuarios;
let rowTable = "";
//let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {

    tableUsuarios = $('#tableUsuarios').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Usuarios/getUsuarios",
            "dataSrc": ""
        },

        "columns": [
            { "data": "per_cedula" },
            { "data": "per_nombre" },
            { "data": "per_apellido" },
            { "data": "usu_correo" },
            //{ "data": "rol_nombre" },
            { "data": "Estado" },
            { "data": "options" }
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
        "order": [[0, "asc"]]
    });


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
            let intTipoRol = document.querySelector('#cmb_rol').value;
            let strPassword = document.querySelector('#txt_Password').value;


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
                            $('#modalFormUsu').modal("hide");
                            formUsuario.reset();
                            swal("Usuarios", objData.msg, "success");
                            tableUsuarios.api().ajax.reload();
                        } else {
                            swal("Error", objData.msg, "error");
                        }
                    }


                }

            }
        }
    }





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


window.addEventListener('load', function () {
    //fntRolAsig();
}, false);

//Se ejecuta en los eventos de Controles
$(document).ready(function () {
    $("#txt_dni").blur(function () {
        let valor = document.querySelector('#txt_dni').value;
        /*if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }*/
    });

});

$(document).ready(function () {
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
});

function fntRolAsig() {
    var ajaxUrl = base_url + '/Usuarios/getRolesUsu';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            if (document.querySelector("#cmb_rol")) {//Control para Vista de Perfil y Usurior no error
                document.querySelector('#cmb_rol').innerHTML = request.responseText;
                document.querySelector('#cmb_rol').value = 0;
                $('#cmb_rol').selectpicker('render');
            }

        }
    }

}


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
    //rowTable = element.parentNode.parentNode.parentNode; //Captura toda la fila seleccionada
    //console.log(rowTable);
    document.querySelector('#titleModal').innerHTML = "Actualizar Datos";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Usuarios/getUsuario/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                //txt_Password
                document.querySelector("#txth_ids").value = objData.data.Ids;
                document.querySelector("#txth_perids").value = objData.data.per_id;
                document.querySelector("#txth_eusuids").value = objData.data.eusu_id;
                document.querySelector("#txt_dni").value = objData.data.Dni;
                $("#txt_dni").prop("readonly", true);
                document.querySelector("#dtp_fecha_nacimiento").value = objData.data.FechaNac;
                document.querySelector("#txt_nombre").value = objData.data.Nombre;
                document.querySelector("#txt_apellido").value = objData.data.Apellido;
                document.querySelector("#txt_telefono").value = objData.data.Telefono;
                document.querySelector("#txt_direccion").value = objData.data.Direccion;
                document.querySelector("#txt_alias").value = objData.data.Alias;
                document.querySelector("#txt_correo").value = objData.data.usu_correo;
                document.querySelector("#cmb_rol").value = objData.data.RolID;
                document.querySelector("#txt_Password").value = objData.data.strPassword;
                $('#cmb_rol').selectpicker('render');
                document.querySelector("#cmb_genero").value = objData.data.Genero;
                $('#cmb_genero').selectpicker('render');

                if (objData.data.Estado == 1) {
                    document.querySelector("#cmb_estado").value = 1;
                } else {
                    document.querySelector("#cmb_estado").value = 2;
                }
                $('#cmb_estado').selectpicker('render');
            }
        }

        $('#modalFormUsu').modal('show');
    }
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
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Usuarios/delUsuario';
            var strData = "Ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableUsuarios.api().ajax.reload(function () {

                        });
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}


function openModal() {
    rowTable = "";
    document.querySelector('#txth_ids').value = "";
    $("#txt_dni").prop("readonly", false);
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector("#formUsu").reset();
    $('#modalFormUsu').modal('show');
}

function openModalPerfil() {
    $('#modalFormPerfil').modal('show');
}

