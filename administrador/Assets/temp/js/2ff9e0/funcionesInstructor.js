let tableInstructor;
let tablePersonaBuscar;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function () {
    tableInstructor = $('#tableInstructor').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Instructor/getInstructor",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Ids" },
            { "data": "Cedula" },
            { "data": "Nombres" },
            { "data": "Fecha" },
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
            }*/
            /* {
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Esportar a CSV",
                "className": "btn btn-info"
            } */
        ],

        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[0, "desc"]]  //Orden por defecto 1 columna
    });

    //NUEVO
    if (document.querySelector("#formInstructor")) {//Verifica si existe el formulario
        var formInstructor = document.querySelector("#formInstructor");//Nombre del formulario 
        formInstructor.onsubmit = function (e) {//Se ejecuta en el Summit
            e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
            //Captura de Campos
            var Ids = document.querySelector('#txth_ids').value;
            var lin_nombre = document.querySelector('#txt_lin_nombre').value;
            var estado = document.querySelector('#cmb_estado').value;
            if (lin_nombre == '' || estado == '')//Validacin de Campos
            {
                swal("Atención", "Todos los campos son obligatorios.", "error");
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
            //Variable Request para los navegadores segun el Navegador (egde,firefox,chrome)
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Instructor/setInstructor';
            var formData = new FormData(formInstructor);//Objeto de Formulario capturado
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {//Responde  
                    //console.log(request.responseText); //Ver el Retorno             
                    var objData = JSON.parse(request.responseText);//Casting Object
                    if (objData.status) {

                        $('#modalFormInstructor').modal("hide");//Oculta el Modal
                        formInstructor.reset();//Limpiar los campos del formulario
                        swal("Instructors", objData.msg, "success");
                        tableInstructor.api().ajax.reload(function () {//Actualizar o refrescar el Datatable de ROL 
                            //Asignar evetos para que no se pierdasn

                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }


        }
    }

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
            let intTipoRol = 10;//Instructor por defecto//document.querySelector('#cmb_rol').value;
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
                            $('#txtCodigoPersona').val(strDni);
                            //$('#txt_ids').val(data.data['Ids']);
                            $('#txt_cedula').val(strDni);
                            $('#txt_nombres').val(strNombre + ' ' + strApellido);
                            $('#modalFormUsu').modal("hide");
                            formUsuario.reset();
                            swal("Usuarios", objData.msg, "success");
                            //tableUsuarios.api().ajax.reload();
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
}, false);


function openModal() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Registro";
    document.querySelector("#formInstructor").reset();
    $('#modalFormInstructor').modal('show');
}



//FUNCION PARA VISTA DE REGISTRO
function fntViewInstructor(ids) {
    var ids = ids;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Instructor/getInstructorIds/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                var estadoReg = objData.data.Estado == 1 ?
                    '<span class="badge badge-success">Activo</span>' :
                    '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_dni").innerHTML = objData.data.Cedula;
                document.querySelector("#lbl_nombres").innerHTML = objData.data.Nombres;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng;
                $('#modalViewInstructor').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}



function fntDeleteInstructor(ids) {
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
            var ajaxUrl = base_url + '/Instructor/delInstructor';
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


$(document).ready(function () {
    //Nueva Orden
    $("#btnNewInstructor").click(function () {
        //eliminarStores();
        window.location = base_url + '/Instructor/nuevo';//Retorna al Portal Principal
    });

    $("#cmd_retornar").click(function () {
        //eliminarStores();
        window.location = base_url + '/Instructor/instructor';//Retorna al Portal Principal
    });

    //Buscar Persona
    $("#txtCodigoPersona").keyup(function (e) {
        e.preventDefault();
        let codigo = $(this).val();
        if (codigo.length >= 4 && codigo != "") {
            buscarPersonaDni(codigo);
        }

    });

    $("#txt_dni").blur(function () {
        /*let valor = document.querySelector('#txt_dni').value;
        if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }*/
    });


    //https://api.jqueryui.com/datepicker/
    $('.date-picker').datepicker({
        autoSize: true,
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: monthNames,
        //changeMonth: true,
        //changeYear: true,
        showButtonPanel: true,
        dateFormat: "yy-mm-dd",
        showDays: false,
        onClose: function (dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
        }
    });


});

function mostrarListaPersona() {
    tablePersonaBuscar = $('#tablePersonas').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Persona/getPersonabuscar",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Cedula" },
            { "data": "Nombre" },
            { "data": "Apellido" },
            { "data": "options" }

        ],
        "columnDefs": [
            //{ 'className': "textcenter", "targets": [3] },//Agregamos la clase que va a tener la columna
            //{ 'className': "textright", "targets": [4] },
            // { 'className': "textcenter", "targets": [ 5 ] }
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    });

}


function openModalBuscarPersona() {
    rowTable = "";
    mostrarListaPersona();
    //document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#titleModal').innerHTML = "Buscar Personas";
    //document.querySelector("#formProductos").reset();
    $('#modalViewPersona').modal('show');
}

function buscarPersonaDni(codigo) {
    let link = base_url + '/Persona/getPersonaIdDni';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "codigo": codigo,
        },
        success: function (data) {
            if (data.status) {//Iva
                //console.log(data);

                $('#txtCodigoPersona').val(data.data['Cedula']);
                $('#txth_per_id').val(data.data['Ids']);
                $('#txt_cedula').val(data.data['Cedula']);
                $('#txt_nombres').val(data.data['Nombre'] + ' ' + data.data['Apellido']);

                //$('#txtCantidadItem').removeAttr("disabled");
                //$('#txtPrecioItem').removeAttr("disabled");

            } else {
                limpiarDatos();
                //$('#txtPrecioItem').attr("disabled","disabled");
                //$('#txtCantidadItem').attr("disabled","disabled");
                swal("Atención!", "No Existen Datos", "error");
            }
            $('#modalViewPersona').modal('hide')
        },
        dataType: "json"
    });
}

function limpiarDatos() {
    $('#txtCodigoPersona').val("");
    $('#txth_ids').val("");
    $('#txth_per_id').val("");
    $('#txt_cedula').val("");
    $('#txt_nombres').val("");
    //$('#lbl_Ruc').text("");

}

function openModalPersona() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#titleModal').innerHTML = "Nueva Persona";
    document.querySelector("#formPersona").reset();
    $('#modalFormPersona').modal('show');
}




function fntRolAsig() {
    var ajaxUrl = base_url + '/Usuarios/getRolesUsu';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            if (document.querySelector("#cmb_rol")) {//Control para Vista de Perfil y Usurior no error
                document.querySelector('#cmb_rol').innerHTML = request.responseText;
                document.querySelector('#cmb_rol').value = 10;//Instructor
                //$('#cmb_rol').attr('readonly','readonly');
                $('#cmb_rol').attr("disabled", "disabled");
                $('#cmb_rol').selectpicker('render');
            }

        }
    }

}


function guardarInstructor(accion) {
    //let accion=($('#cmd_guardar').html()=="Guardar")?'Create':'edit';    
    let Ids = document.querySelector('#txth_ids').value;
    let per_id = document.querySelector('#txth_per_id').value;
    let centro = document.querySelector('#cmb_CentroAtencion').value;
    let txt_cedula = document.querySelector('#txt_cedula').value;
    let txt_horas_asignadas = document.querySelector('#txt_horas_asignadas').value;
    let txt_horas_extras = document.querySelector('#txt_horas_extras').value;
    let element = document.getElementById('cmb_Salon');//obtienes los itmes seleccionados
    let selectedSalon = Array.from(element.selectedOptions)
        .map(option => option.value)

    let selecionados = "";

    $("#dts_horas tr td input[type='checkbox']:checked").each(function () {
        selecionados += $(this).attr("id") + ",";
    });

    selecionados = selecionados.slice(0, selecionados.length - 1);
    if (per_id == '' || txt_cedula == '' || txt_horas_asignadas == '' || txt_horas_extras == '' || selecionados == '' || centro == '0') {
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

    var dataInstructor = new Object();
    dataInstructor.ids = Ids;
    dataInstructor.per_id = per_id;
    dataInstructor.cat_id = centro;
    dataInstructor.cedula = txt_cedula;
    dataInstructor.horas_asignadas = txt_horas_asignadas;
    dataInstructor.horas_extras = txt_horas_extras;
    dataInstructor.semana_horas = selecionados;
    dataInstructor.salones = selectedSalon.toString();

    //sessionStorage.dataInstructor = JSON.stringify(dataInstructor);

    let link = base_url + '/instructor/ingresarInstructor';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            //"dts_detalle": (accion == "Create") ? listaDetalle() : listaPedidoDetTemp(),
            "instructor": JSON.stringify(dataInstructor),
            "accion": accion
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('cabeceraOrden');
                swal("Instructor", data.msg, "success");
                window.location = base_url + '/instructor/instructor';
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });
}

function fntSalones(ids) {
    //$('#cmb_Salon').html('<option value="">SELECCIONAR CENTRO</option>');
    $('#cmb_Salon').html('');
    if (ids != 0) {
        let link = base_url + '/Salon/bucarSalonCentro';
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                "Ids": ids
            },
            success: function (data) {
                if (data.status) {
                    $('#cmb_Salon').prop('disabled', false);
                    var result = data.data;
                    for (var i = 0; i < result.length; i++) {
                        $('#cmb_Salon').append('<option value="' + result[i].Ids + '">' + result[i].Nombre + '</option>');
                    }
                } else {
                    swal("Error", data.msg, "error");
                }
            },
            dataType: "json"
        });
    } else {
        $('#cmb_Salon').prop('disabled', true);
        $('#cmb_Salon').html('');
        swal("Información", "Seleccionar un Centro de Atención", "info");
    }

}


/**************** GUARDAR DATOS PERSONA  ******************/
function guardarPersona() {
    var per_cedula = document.querySelector('#txt_per_cedula').value;
    var per_nombre = document.querySelector('#txt_per_nombre').value;
    var per_apellido = document.querySelector('#txt_per_apellido').value;
    var per_fecha_nacimiento = document.querySelector('#dtp_fecha_nacimiento').value;
    var per_telefono = document.querySelector('#txt_per_telefono').value;
    var per_direccion = document.querySelector('#txt_per_direccion').value;
    var per_genero = document.querySelector('#txt_per_genero').value;
    if (per_cedula == '' || per_nombre == '' || per_apellido == '' || per_fecha_nacimiento == '' || per_telefono == '' || per_direccion == '' || per_genero == '')//Validacin de Campos
    {
        swal("Atención", "Todos los campos son obligatorios.", "error");
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

    var dataPersona = new Object();
    //dataPersona.ids = Ids;
    dataPersona.per_cedula = per_cedula;
    dataPersona.per_nombre = per_nombre;
    dataPersona.per_apellido = per_apellido;
    dataPersona.per_fecha_nacimiento = per_fecha_nacimiento;
    dataPersona.per_telefono = per_telefono;
    dataPersona.per_direccion = per_direccion;
    dataPersona.per_genero = per_genero;
    //sessionStorage.dataPersona = JSON.stringify(dataPersona);
    let link = base_url + '/Persona/ingresarPersonaDatos';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "persona": JSON.stringify(dataPersona),
            "accion": "Create"
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('dataPersona');
                swal("Mensaje", data.msg, "success");
                $('#txtCodigoPersona').val(per_cedula);
                $('#txth_per_id').val(data.numero);
                $('#txt_cedula').val(per_cedula);
                $('#txt_nombres').val(per_nombre + " " + per_apellido);
                $('#modalFormPersona').modal("hide");
                limpiarTexboxPersona();
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });

}

function limpiarTexboxPersona() {
    $('#txt_per_cedula').val("");
    $('#dtp_fecha_nacimiento').val("");
    $('#txt_per_nombre').val("");
    $('#txt_per_apellido').val("");
    $('#txt_per_telefono').val("");
    $('#txt_per_direccion').val("");

}







