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

    $("#btn_next_one").click(function () {
		fnt_next_one();
	});
    
    $("#btn_back_one").click(function () {
		fnt_back_one();
	});

    $("#btn_guardarModulo").click(function () {
		fnt_saveEmpModulo();
	});

    $("#btn_next_one_rol").click(function () {
		fnt_next_one_rol();
	});
    
    $("#btn_back_one_rol").click(function () {
		fnt_back_one_rol();
	});
    $("#btn_guardarRoles").click(function () {
		fnt_saveEmpRoles();
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

//EMPRESA MODULO//

function fntEmpresaModulos(ids) {
    if (ids != 0) {
        sessionStorage.removeItem("dts_EmpresaModulo");
        sessionStorage.removeItem("OrdenadoIds");
        let url = base_url + '/Empresa/getModulosPorEmpresa';
        var metodo = 'POST';
        var datos = { Ids: ids };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            if (data.status) {
                var c = 0;
                $("#cmb_Emp_modulos").html('');
                var result = data.data.Modulo;
                let arrayList = result.map(function(objeto) {//Obtiene y lo retorna a un array identado
                        return { ids: objeto.mod_id, Nombre: objeto.Nombre };
                    });
                // Utilizar map para crear un nuevo array solo con la propiedad 'ids'
                let arrayIds = result.map(function(objeto) {
                    return objeto.mod_id;
                });
                sessionStorage.OrdenadoIds = arrayIds;//Solos los IDS
                sessionStorage.dts_EmpresaModulo = JSON.stringify(arrayList);//Todos modsulos asignados a la empresa
                actualizarEmpModulo();
            } else {
                swal("Atención", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        swal("Información", "Seleccionar un Empresa", "info");
    }

}

function actualizarEmpModulo() {
    const empresaModuloData = sessionStorage.getItem('dts_EmpresaModulo');
    const $cmbEmpModulos = $("#cmb_Emp_modulos");

    $cmbEmpModulos.empty(); // Limpia el combo primero

    if (!empresaModuloData) {
        return; // No hay datos, salimos
    }

    try {
        const result = JSON.parse(empresaModuloData);
        if (Array.isArray(result) && result.length > 0) {
            const options = result.map(item => 
                `<option value="${item.ids}">${item.Nombre}</option>`
            ).join('');
            $cmbEmpModulos.html(options);
        }
    } catch (error) {
        console.error("Error al parsear dts_EmpresaModulo:", error);
    }
}

function fnt_next_one(){
    let element = document.getElementById('cmb_modulos');//obtienes los itmes seleccionados
    const selectEmpMod = Array.from(element.selectedOptions).map(option => {
        return {
            Ids: option.value,
            Nombre: option.textContent // Dividir el contenido para obtener el nombre
        };
    });
    for (var i = 0; i < selectEmpMod.length; i++) {
        var arrayOrdenado = ordenarSecuencias(sessionStorage.OrdenadoIds, selectEmpMod[i].Ids);
        sessionStorage.OrdenadoIds = arrayOrdenado;
    }
    actualizarModulos();
    actualizarEmpModulo();
}



function fnt_back_one() {
    const element = document.getElementById('cmb_Emp_modulos');
    const selectedIds = Array.from(element.selectedOptions).map(option => option.value);

    let result = JSON.parse(sessionStorage.getItem('dts_EmpresaModulo')) || [];

    // Filtrar eliminando los seleccionados
    const arrayResult = result.filter(item => !selectedIds.includes(item.ids));

    sessionStorage.setItem('dts_EmpresaModulo', JSON.stringify(arrayResult));

    // Actualiza la matriz de IDs ordenados
    const arrayIds = arrayResult.map(item => item.ids);
    sessionStorage.setItem('OrdenadoIds', arrayIds.join(','));

    actualizarEmpModulo();
}



function ordenarSecuencias(arrayString, nuevaSecuencia) {
    array = arrayString.split(',');//Convierte la cadena a un string
    let indiceExistente = array.indexOf(nuevaSecuencia);
    // Si la nuevaSecuencia no existe, agregarla al array
    if (indiceExistente === -1) {
        array.push(nuevaSecuencia);
    }
    // Ordenar el array alfabéticamente
    array.sort();
    // Retornar el array ordenado
    return array;
}

function actualizarModulos() {
    const ordenadoIds = sessionStorage.getItem("OrdenadoIds");
    const dtsModulos = sessionStorage.getItem("dts_Modulos");

    if (!ordenadoIds || !dtsModulos) return;

    const idsOrdenados = ordenadoIds.split(",");
    const modulos = JSON.parse(dtsModulos);
    const arrayList = [];

    idsOrdenados.forEach((id, index) => {//Recorre los Orndenasos
        const modulo = modulos.find(m => m.ids === id);//Busca el ID en modulos
        if (modulo) {
            arrayList.push({//agrega y retonrna en nuevo array
                ids: id,
                Nombre: modulo.Nombre
            });
        }
    });

    sessionStorage.setItem("dts_EmpresaModulo", JSON.stringify(arrayList));
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
    sessionStorage.removeItem("OrdenadoIds");
    sessionStorage.dts_Modulos = JSON.stringify(arrayList);

}

function fnt_saveEmpModulo(){
    let accion ="Create";// ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let EmpId=($('#cmb_empresa').val()!=0)?$('#cmb_empresa').val():0;
    if (EmpId != 0) {
        //arrayModulo = JSON.parse(sessionStorage.dts_Modulos);
        let url = base_url + '/Empresa/actualizarEmpresaModulo';
        var metodo = 'POST';
        var datos= {
            eusu_id: EmpId,
            ids: sessionStorage.OrdenadoIds,
            accion: accion
        };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            //console.log(data);
            if (data.status) {
                swal("Atención", data.msg, "success");
                //var result = data.data.Modulo;         
            } else {
                swal("Error", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        //$("#cmb_centro").prop("disabled", true);
        swal("Información", "Seleccionar un Empresa", "info");
    }

}
/**
 * Configuracion de ROLES
 */ 
function fntEmpresaRoles(ids) {
    if (ids != 0) {
        sessionStorage.removeItem("dts_EmpresaRol");
        sessionStorage.removeItem("OrdenadoIdsRol");
        let url = base_url + '/Empresa/getRolesPorEmpresa';
        var metodo = 'POST';
        var datos = { Ids: ids };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            if (data.status) {
                var c = 0;
                $("#cmb_Emp_roles").html('');
                var result = data.data.Modulo;
                let arrayList = result.map(function(objeto) {
                        return { ids: objeto.rol_id, Nombre: objeto.Nombre };
                    });
                // Utilizar map para crear un nuevo array solo con la propiedad 'ids'
                let arrayIds = result.map(function(objeto) {
                    return objeto.rol_id;
                });
                sessionStorage.OrdenadoIdsRol = arrayIds;
                sessionStorage.dts_EmpresaRol = JSON.stringify(arrayList);
                actualizarListRoles();
            } else {
                swal("Atención", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        //$("#cmb_centro").prop("disabled", true);
        swal("Información", "Seleccionar un Empresa", "info");
    }

}

function fnt_next_one_rol() {
    const element = document.getElementById('cmb_roles');
    const selectedRoles = Array.from(element.selectedOptions).map(option => ({
        ids: option.value,
        Nombre: option.textContent.trim()
    }));

    let arrayRoles = JSON.parse(sessionStorage.getItem('dts_EmpresaRol')) || [];

    selectedRoles.forEach(role => {
        const exists = arrayRoles.some(r => r.ids === role.ids);
        if (!exists) {
            arrayRoles.push({ ids: role.ids, Nombre: role.Nombre });
        }
    });

    sessionStorage.setItem('dts_EmpresaRol', JSON.stringify(arrayRoles));
    actualizarListRoles();
}

function fnt_back_one_rol() {
    const element = document.getElementById('cmb_Emp_roles');
    const selectedIds = Array.from(element.selectedOptions).map(option => option.value);
    alert(JSON.stringify(selectedIds))

    let roles = JSON.parse(sessionStorage.getItem('dts_EmpresaRol')) || [];

    // Filtra roles que no estén seleccionados (mantiene los no seleccionados)
    const updatedRoles = roles.filter(role => !selectedIds.includes(role.ids));

    sessionStorage.setItem('dts_EmpresaRol', JSON.stringify(updatedRoles));
    actualizarListRoles();
}


function actualizarListRoles() {
    const nData = sessionStorage.getItem('dts_EmpresaRol');
    const $combo = $("#cmb_Emp_roles");

    $combo.empty(); // Limpia el combo primero

    if (!nData) {
        return; // No hay datos, salimos
    }

    try {
        const result = JSON.parse(nData);
        if (Array.isArray(result) && result.length > 0) {
            const options = result.map(item => 
                `<option value="${item.ids}">${item.Nombre}</option>`
            ).join('');
            $combo.html(options);
        }
    } catch (error) {
        console.error("Error al parsear dts_EmpresaModulo:", error);
    }
}


function fnt_saveEmpRoles(){
    let accion ="Create";// ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let EmpId=($('#cmb_empresa2').val()!=0)?$('#cmb_empresa2').val():0;
    if (EmpId != 0) {
        result = JSON.parse(sessionStorage.dts_EmpresaRol);
        let arrayIds = result.map(function(objeto) {
            return objeto.ids;
        });
        let url = base_url + '/Empresa/actualizarEmpresaRoles';
        var metodo = 'POST';
        var datos= {
            eusu_id: EmpId,
            ids: arrayIds.join(","),//Conviete un array a cadena
            accion: accion
        };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            if (data.status) {
                swal("Atención", data.msg, "success");
                //var result = data.data.Modulo;         
            } else {
                swal("Error", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        //$("#cmb_centro").prop("disabled", true);
        swal("Información", "Seleccionar un Empresa", "info");
    }

}


/*
EMPRESA MODULO ROLES
*/
function fntEmpresaModuloRoles(ids) {
    if (ids != 0) {
        sessionStorage.removeItem("dts_EmpresaModuloRol");
        sessionStorage.removeItem("dts_RolModuloEmpresa");
        let url = base_url + '/Empresa/getModuloRolesPorEmpresa';
        var metodo = 'POST';
        var datos = { Ids: ids };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            if (data.status) {
                var c = 0;
                //$("#cmb_empresa_modulo_roles").html('');
                var result = data.data.Modulo;
                let arrayList = result.map(function(objeto) {
                        return { ids: objeto.rol_id, Nombre: objeto.Nombre };
                    });
                result = data.data.EmpresaModulo;
                let arrayList2 = result.map(function(objeto) {
                        return { ids: objeto.mod_id, Nombre: objeto.Nombre };
                    });
                // Utilizar map para crear un nuevo array solo con la propiedad 'ids'
                //let arrayIds = result.map(function(objeto) {
                //    return objeto.rol_id;
                //});
                //sessionStorage.OrdenadoIdsRol = arrayIds;
                sessionStorage.dts_EmpresaModuloRol = JSON.stringify(arrayList);
                sessionStorage.dts_RolModuloEmpresa = JSON.stringify(arrayList2);
                ActualizarListModuloRoles();
                ActualizarListRolModuloEmpresa();
            } else {
                swal("Atención", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        //$("#cmb_centro").prop("disabled", true);
        swal("Información", "Seleccionar un Empresa Modulo", "info");
    }

}

function ActualizarListModuloRoles(){
    if (sessionStorage.dts_EmpresaModuloRol) {
        result = JSON.parse(sessionStorage.dts_EmpresaModuloRol);
        $("#cmb_empresa_modulo_roles").html('');
        if (result.length > 0) {
            for (var i = 0; i < result.length; i++) {
                $("#cmb_empresa_modulo_roles").append(
                    '<option value="' + result[i].ids + '"  >' +
                    result[i].Nombre +
                    "</option>"
                );
                
            }
        }
    }
}

function ActualizarListRolModuloEmpresa(){
    if (sessionStorage.dts_RolModuloEmpresa) {
        result = JSON.parse(sessionStorage.dts_RolModuloEmpresa);
        $("#list_EmpresaModuloroles").html('');
        if (result.length > 0) {
            for (var i = 0; i < result.length; i++) {
                $("#list_EmpresaModuloroles").append(
                    '<option value="' + result[i].ids + '"  >' +
                    result[i].Nombre +
                    "</option>"
                );
                
            }
        }
    }
}


function fntListarModuloporRol(ids) {
    if (ids != 0) {
        //sessionStorage.removeItem("dts_EmpresaModuloRol");
        //sessionStorage.removeItem("dts_RolModuloEmpresa");
        let url = base_url + '/Empresa/getModuloRolesPorEmpresa';
        var metodo = 'POST';
        var datos = { Ids: ids };
        peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
            // Manejar el éxito de la solicitud aquí
            if (data.status) {
                /*var c = 0;
                //$("#cmb_empresa_modulo_roles").html('');
                var result = data.data.Modulo;
                let arrayList = result.map(function(objeto) {
                        return { ids: objeto.rol_id, Nombre: objeto.Nombre };
                    });
                result = data.data.EmpresaModulo;
                let arrayList2 = result.map(function(objeto) {
                        return { ids: objeto.mod_id, Nombre: objeto.Nombre };
                    });
                // Utilizar map para crear un nuevo array solo con la propiedad 'ids'
                //let arrayIds = result.map(function(objeto) {
                //    return objeto.rol_id;
                //});
                //sessionStorage.OrdenadoIdsRol = arrayIds;
                sessionStorage.dts_EmpresaModuloRol = JSON.stringify(arrayList);
                sessionStorage.dts_RolModuloEmpresa = JSON.stringify(arrayList2);
                ActualizarListModuloRoles();
                ActualizarListRolModuloEmpresa();*/
            } else {
                swal("Atención", data.msg, "error");
            }
        }, function (jqXHR, textStatus, errorThrown) {
            // Manejar el error de la solicitud aquí
            console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        });
    } else {
        //$("#cmb_centro").prop("disabled", true);
        swal("Información", "Seleccionar un Empresa Modulo", "info");
    }

}


