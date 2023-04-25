
var tableRoles;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function(){

	tableRoles = $('#tableRoles').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
            "url": cdnTable       
        },
        "ajax":{
            "url": " "+base_url+"/Roles/getRoles",
            "dataSrc":""
        },
        "columns":[
            {"data":"rol_id"},
            {"data":"rol_nombre"},
            {"data":"estado_logico"},
            {"data":"options"}
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order":[[0,"desc"]]  //Orden por defecto 1 columna
    });

    //Verificas los elementos conl clase valid para controlar que esten ingresados
    let elementsValid = document.getElementsByClassName("valid");
    for (let i = 0; i < elementsValid.length; i++) { 
        if(elementsValid[i].classList.contains('is-invalid')) { 
            swal("Atención", "Por favor verifique los campos ingresados (Color Rojo)." , "error");
            return false;
        } 
    } 
    //NUEVO ROL
    var formRol = document.querySelector("#formRol");//Nombre del formulario 
    formRol.onsubmit = function(e) {//Se ejecuta en el Summit
        e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
        //Captura de Campos
        var rol_id = document.querySelector('#txth_rol_id').value;
        var rol_nombre = document.querySelector('#txt_rol_nombre').value;
        var estado = document.querySelector('#cmb_estado').value;        
        if(rol_nombre == '' || estado == '')//Validacin de Campos
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        //Variable Request para los navegadores segun el Navegador (egde,firefox,chrome)
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Roles/setRol'; 
        var formData = new FormData(formRol);//Objeto de Formulario capturado
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){//Responde  
                //console.log(request.responseText); //Ver el Retorno             
                var objData = JSON.parse(request.responseText);//Casting Object
                if(objData.status) {
                    $('#modalFormRol').modal("hide");//Oculta el Modal
                    formRol.reset();//Limpiar los campos del formulario
                    swal("Roles de usuario", objData.msg ,"success");
                    tableRoles.api().ajax.reload(function(){//Actualizar o refrescar el Datatable de ROL 
                        //Asignar evetos para que no se pierdasn
                        fntEditRol();
                        fntDelRol();
                        fntPermisos();
                    });
                }else{
                    swal("Error", objData.msg , "error");
                }              
            } 
        }

        
    }

  

});

//$('#tableRoles').DataTable();

function openModal(){
    document.querySelector('#txth_rol_id').value ="";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    document.querySelector("#formRol").reset();
	$('#modalFormRol').modal('show');
}

window.addEventListener('load', function() {
    fntEditRol();
    fntDelRol();
    fntPermisos();
}, false);

//Editar Registro
function fntEditRol(){
    var btnEdit = document.querySelectorAll(".btnEditRol");//extrae todos los elementos con esa clase
    btnEdit.forEach(function(btnEdit) {
        btnEdit.addEventListener('click', function(){//Funcion al darle click
            //Cambiar propiedades actualizar
            document.querySelector('#titleModal').innerHTML ="Actualizar Rol";
            document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
            document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
            document.querySelector('#btnText').innerHTML ="Actualizar";

            var idrol = this.getAttribute("rl");//Accede al Elemnento Selecinado
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl  = base_url+'/Roles/getRol/'+idrol;
            request.open("GET",ajaxUrl ,true);
            request.send();

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    //console.log(request.responseText);
                    var objData = JSON.parse(request.responseText);//Convierte un Objeto a JSON
                    if(objData.status){
                        document.querySelector("#txth_rol_id").value = objData.data.rol_id;
                        document.querySelector("#txt_rol_nombre").value = objData.data.rol_nombre;
                        if(objData.data.estado_logico == 1){
                            var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';//Valores por Defecto
                        }else{
                            var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';//Valores por Defecto
                        }
                        var htmlSelect = `${optionSelect}
                                          <option value="1">Activo</option>
                                          <option value="2">Inactivo</option>
                                        `;
                        document.querySelector("#cmb_estado").innerHTML = htmlSelect;
                        $('#modalFormRol').modal('show');
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
            }
            
        });
    });
}

function fntDelRol(){
    var btnDel = document.querySelectorAll(".btnDelRol");
    btnDel.forEach(function(btnDel) {
        btnDel.addEventListener('click', function(){
            var idrol = this.getAttribute("rl");//Valor del elemento que se le dio click
            swal({//Crear el Modal
                title: "Eliminar Registro",
                text: "¿Realmente quiere eliminar el Registro?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {
                
                if (isConfirm) {
                    //Enviar las variables por Ajax
                    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    var ajaxUrl = base_url+'/Roles/delRol/';
                    var strData = "ids="+idrol;// Parametros a enviar
                    request.open("POST",ajaxUrl,true);
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    request.send(strData);
                    request.onreadystatechange = function(){
                        if(request.readyState == 4 && request.status == 200){
                            var objData = JSON.parse(request.responseText);
                            if(objData.status)
                            {
                                swal("Eliminar!", objData.msg , "success");
                                tableRoles.api().ajax.reload(function(){
                                    fntEditRol();
                                    fntDelRol();
                                    //fntPermisos();
                                });
                            }else{
                                swal("Atención!", objData.msg , "error");
                            }
                        }
                    }
                }

            });

        });
    });
}

function fntPermisos(){
    var btnPermisosRol = document.querySelectorAll(".btnPermisosRol");
    btnPermisosRol.forEach(function(btnPermisosRol) {
        btnPermisosRol.addEventListener('click', function(){

            var idrol = this.getAttribute("rl");
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Permisos/getPermisosRol/'+idrol;
            request.open("GET",ajaxUrl,true);
            request.send();
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    document.querySelector('#contentAjax').innerHTML = request.responseText;
                    $('.modalPermisos').modal('show');
                    document.querySelector('#formPermisos').addEventListener('submit',fntGuardarPermisos,false);//Agregar el Evento
                }
            }
            
            
        });
    });
}

function fntGuardarPermisos(evnet){
    evnet.preventDefault();
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Permisos/setPermisos'; 
    var formElement = document.querySelector("#formPermisos");
    var formData = new FormData(formElement);
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                swal("Permisos de usuario", objData.msg ,"success");
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
    
}


