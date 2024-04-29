//Integrar la libreria Cedula Ruc
document.write(`<script src="${base_url}/Assets/js/cedulaRucPass.js"></script>`);//
var tableEmpresa;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function () {
    tableEmpresa = $('#tableEmpresa').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Empresa/getEmpresas",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Ruc" },
            { "data": "Razon" },
            { "data": "Nombre" },
            { "data": "Direccion" },
            { "data": "Correo" },
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

    if (typeof nEmpresa !== "undefined") {
        fnt_inicio(nEmpresa);
    } 

    //NUEVO 
    var formEmpresa = document.querySelector("#formEmpresa");//Nombre del formulario 
    if (formEmpresa === null || typeof formEmpresa === 'undefined') {
        //SI NO TIENE VALOR NO INGRESA
    } else {

        formEmpresa.onsubmit = function (e) {//Se ejecuta en el Summit
            e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
            //Captura de Campos
            var Ids = document.querySelector('#txth_ids').value;
            var emp_ruc = document.querySelector('#txt_emp_ruc').value;
            var emp_razon_social = document.querySelector('#txt_emp_razon_social').value;
            var emp_nombre_comercial = document.querySelector('#txt_emp_nombre_comercial').value;
            var emp_direccion = document.querySelector('#txt_emp_direccion').value;
            var emp_correo = document.querySelector('#txt_emp_correo').value;
            var emp_ruta_logo = document.querySelector('#txt_emp_ruta_logo').value;
            var emp_moneda = document.querySelector('#cmb_moneda').value;
            var estado = document.querySelector('#cmb_estado').value;
            if (emp_ruc == '' || emp_razon_social == '' || emp_nombre_comercial == '' || emp_direccion == '' || emp_correo == '' || emp_ruta_logo == '' || emp_moneda == '' || estado == '') {//Validacin de Campos
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
            var ajaxUrl = base_url + '/Empresa/setEmpresa';
            var formData = new FormData(formEmpresa);//Objeto de Formulario capturado
            request.open("POST", ajaxUrl, true);
            request.send(formData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {//Responde     
                    var objData = JSON.parse(request.responseText);//Casting Object
                    if (objData.status) {
                        $('#modalFormEmpresa').modal("hide");//Oculta el Modal
                        formEmpresa.reset();//Limpiar los campos del formulario
                        swal("Empresa", objData.msg, "success");
                        tableEmpresa.api().ajax.reload(function () {//Actualizar o refrescar el Datatable de ROL 
                            //Asignar evetos para que no se pierdasn
                            //fntEditRol();
                            //fntDelRol();
                            //fntPermisos();
                        });
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                }
            }


        }
    }




});


function openModal() {
    document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Empresa";
    document.querySelector("#formEmpresa").reset();
    $('#modalFormEmpresa').modal('show');
}

/*window.addEventListener('load', function() {
    fntMoneda();
   
}, false);*/

//Se ejecuta en los eventos de Controles
$(document).ready(function () {
    $("#txt_emp_ruc").blur(function () {
        let valor = document.querySelector('#txt_emp_ruc').value;
        if (!validarDocumento(valor)) {
            swal("Error", "Error de DNI", "error");
        }

    });


    $("#btn_next_all").click(function () {
		fnt_next_all();
	});
    $("#btn_next_one").click(function () {
		fnt_next_one();
	});
    $("#btn_back_all").click(function () {
		fnt_back_all();
	});
    $("#btn_back_one").click(function () {
		fnt_back_one();
	});

});


function fntMoneda() {
    var ajaxUrl = base_url + '/Empresa/getMoneda';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            document.querySelector('#cmb_moneda').innerHTML = request.responseText;
            document.querySelector('#cmb_moneda').value = 0;
            $('#cmb_moneda').selectpicker('render');
        }
    }

}

//FUNCION PARA VISTA DE REGISTRO
function fntViewEmpresa(ids) {
    var ids = ids;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Empresa/getEmpresa/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                var estadoReg = objData.data.Estado == 1 ?
                    '<span class="badge badge-success">Activo</span>' :
                    '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_ruc").innerHTML = objData.data.Ruc;
                document.querySelector("#lbl_razon").innerHTML = objData.data.Razon;
                document.querySelector("#lbl_nombre").innerHTML = objData.data.Nombre;
                document.querySelector("#lbl_direccion").innerHTML = objData.data.Direccion;
                document.querySelector("#lbl_correo").innerHTML = objData.data.Correo;
                document.querySelector("#lbl_logo").innerHTML = objData.data.Logo;
                document.querySelector("#lbl_moneda").innerHTML = objData.data.Moneda;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng;
                $('#modalViewEmpresa').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}


//Editar Registro
function fntEditEmpresa(ids) {
    document.querySelector('#titleModal').innerHTML = "Actualizar Empresa";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Empresa/getEmpresa/' + ids;
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var objData = JSON.parse(request.responseText);
            if (objData.status) {
                document.querySelector("#txth_ids").value = objData.data.Ids;
                document.querySelector('#txt_emp_ruc').value = objData.data.Ruc;
                document.querySelector('#txt_emp_razon_social').value = objData.data.Razon;
                document.querySelector('#txt_emp_nombre_comercial').value = objData.data.Nombre;
                document.querySelector('#txt_emp_direccion').value = objData.data.Direccion;
                document.querySelector('#txt_emp_correo').value = objData.data.Correo;
                document.querySelector('#txt_emp_ruta_logo').value = objData.data.Logo;
                document.querySelector("#cmb_moneda").value = objData.data.IdMoneda;
                $('#cmb_moneda').selectpicker('render');
                if (objData.data.Estado == 1) {
                    document.querySelector("#cmb_estado").value = 1;
                } else {
                    document.querySelector("#cmb_estado").value = 2;
                }
                $('#cmb_estado').selectpicker('render');
            }
        }
        $('#modalFormEmpresa').modal('show');
    }
}


function fntDeleteEmpresa(ids) {
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
            var ajaxUrl = base_url + '/Empresa/delEmpresa';
            var strData = "ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminar!", objData.msg, "success");
                        tableEmpresa.api().ajax.reload(function () {

                        });
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}

function fntEmpresaModulos(ids) {
    if (ids != 0) {
        let url = base_url + '/Empresa/getModulosPorEmpresa';
        var metodo = 'POST';
        var datos = { Ids: ids };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            if (data.status) {
                //$("#cmb_punto").html('<option value="0">SELECCIONAR</option>');
                //$("#cmb_punto").prop("disabled", false);
                var c = 0;
                $("#cmb_Emp_modulos").html('');
                var result = data.data.Modulo;
                var arrayList = new Array();
                for (var i = 0; i < result.length; i++) {
                    /*$("#cmb_Emp_modulos").append(
                        '<option value="' + result[i].Ids + '"  >' +
                        result[i].Nombre +
                        "</option>"
                    );*/
                    let rowMod = new Object();
                    rowMod.ids = result[i].Ids;
                    rowMod.IdMod = result[i].mod_id;
                    //rowMod.IdPadre = result[i].idPadre;                       
                    rowMod.Nombre = result[i].Nombre;         
                    arrayList[c] = rowMod;
                    c += 1;
                }
                sessionStorage.dts_EmpresaModulo = JSON.stringify(arrayList);
                ActualizarEmpModulo();
            } else {
                swal("Atención", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        $("#cmb_centro").prop("disabled", true);
        swal("Información", "Seleccionar un Empresa", "info");
    }

}

function ActualizarEmpModulo(){
    var arrayList = new Array();
    if (sessionStorage.dts_EmpresaModulo) {
        result = JSON.parse(sessionStorage.dts_EmpresaModulo);
        $("#cmb_Emp_modulos").html('');
        if (result.length > 0) {
            //var array = findAndRemove(Grid, "dia", nDia);
            //sessionStorage.dts_PlaTemporal = JSON.stringify(array);
            for (var i = 0; i < result.length; i++) {
                $("#cmb_Emp_modulos").append(
                    '<option value="' + result[i].Ids + '"  >' +
                    result[i].Nombre +
                    "</option>"
                );
                
            }
        }
    }
}


function fnt_next_all() {
    console.log("fnt_next_all");
    let element = document.getElementById('cmb_modulos');//obtienes los itmes seleccionados
    //let selectedSalon = Array.from(element.selectedOptions)
    //    .map(option => option.value)
    //let opcionesSeleccionadas = Array.from(element.selectedOptions)
    //    .map(option => ({ id: option.IdMod, nombre: option.value }));
    const selectEmpMod = Array.from(element.selectedOptions).map(option => {
        return {
            Ids: option.value,
            Nombre: option.textContent // Dividir el contenido para obtener el nombre
        };
    });
    //console.log(selectEmpMod);
    var arrayList = new Array();
    for (var i = 0; i < selectEmpMod.length; i++) {
        alert(selectEmpMod[i].Ids);
        if (codigoExiste(selectEmpMod[i].Ids, "Ids", sessionStorage.dts_EmpresaModulo)) {//Si no existe lo agrega
            //alert('nuevo');
            let rowMod = new Object();
            rowMod.Ids = selectEmpMod[i].Ids;
            //rowMod.IdMod = selectEmpMod[i].mod_id;
            //rowMod.IdPadre = result[i].idPadre;                       
            rowMod.Nombre = selectEmpMod[i].Nombre;  
            //return rowGrid;
            arrayList = JSON.parse(sessionStorage.dts_EmpresaModulo);
            arrayList[arrayList.length] = rowMod;//objDataRow(nLetIni);
            arrayList.sort();
            sessionStorage.dts_EmpresaModulo = JSON.stringify(arrayList);

        }
    }
    ActualizarEmpModulo();
}
function fnt_next_one(){
    console.log("fnt_next_one");
}
function fnt_back_all(){
    console.log("fnt_back_all");
}
function fnt_back_one(){
    console.log("fnt_back_one");
}

function fnt_inicio(resultEmp) {
    var arrayList = new Array();
    var c = 0;
    for (var i = 0; i < resultEmp.length; i++) {
        let rowInst = new Object();
        rowInst.ids = resultEmp[i].Ids;
        rowInst.Nombre = resultEmp[i].Nombre;
        rowInst.idPadre = resultEmp[i].idPadre;
        arrayList[c] = rowInst;
        c += 1;
    }
    sessionStorage.removeItem("dts_EmpresaModulo");
    sessionStorage.removeItem("dts_Modulos");
    sessionStorage.dts_Modulos = JSON.stringify(arrayList);

}





