var tableSec;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function(){
	tableSec = $('#tableSec').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
            "url": cdnTable       
        },
        "ajax":{
            "url": " "+base_url+"/Secuencias/getSecuencias",
            "dataSrc":""
        },
        "columns":[
            {"data":"Ids"},
            {"data":"Punto"},
            {"data":"Tipo"},
            {"data":"Secuencia"},
            {"data":"Documento"},
            {"data":"Estado"},
            {"data":"options"}
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order":[[0,"desc"]]  //Orden por defecto 1 columna
    });

    //NUEVO ROL
    var formSec = document.querySelector("#formSec");//Nombre del formulario 
    formSec.onsubmit = function(e) {//Se ejecuta en el Summit
        e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
        //Captura de Campos
        var Ids = document.querySelector('#txth_ids').value;
        var sec_punto = document.querySelector('#cmb_punto').value; 
        var sec_tipo = document.querySelector('#txt_sec_tipo').value;
        var sec_numero = document.querySelector('#txt_sec_numero').value;
        var sec_nombre = document.querySelector('#txt_sec_nombre').value;
        var estado = document.querySelector('#cmb_estado').value;        
        if(sec_punto == '' || sec_nombre == '' || sec_numero == '' || sec_tipo == ''){//Validacin de Campos
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
        //Variable Request para los navegadores segun el Navegador (egde,firefox,chrome)
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Secuencias/setSecuencia'; 
        var formData = new FormData(formSec);//Objeto de Formulario capturado
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){//Responde  
                console.log(request.responseText); //Ver el Retorno             
                var objData = JSON.parse(request.responseText);//Casting Object
                if(objData.status) {
                    $('#modalFormSec').modal("hide");//Oculta el Modal
                    formSec.reset();//Limpiar los campos del formulario
                    swal("Secuencias", objData.msg ,"success");
                    tableSec.api().ajax.reload(function(){//Actualizar o refrescar el Datatable de ROL 
                        //Asignar evetos para que no se pierdasn
                        //fntEditRol();
                        //fntDelRol();
                        //fntPermisos();
                    });
                }else{
                    swal("Error", objData.msg , "error");
                }              
            } 
        }

        
    }

  

});

//Se ejecuta en los eventos de Controles
$(document).ready(function() {
    /*$('#cmb_establecimiento').change(function () {        
        if ($('#cmb_establecimiento').val() != 0) {            
            fntPunto();
        } else {
            $('#cmb_punto option').remove();
            //$('#cmb_punto').selectpicker('refresh')
            swal("Error", "Selecione Establecimiento" , "error");
        }
    });*/

    /*$("#ids-sx").keyup(function() {
        //codigo
    });
    $("#btnActionForm").click(function() {
        guardarDatos();
    });*/
});

//$('#tableSec').DataTable();

function openModal(){
    document.querySelector('#txth_ids').value ="";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Documento";
    document.querySelector("#formSec").reset();
	$('#modalFormSec').modal('show');
}



/*function fntPunto(){
    var ids=$('#cmb_establecimiento').val();
        var ajaxUrl = base_url + '/Secuencias/getPunto/' + ids;
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET", ajaxUrl, true);
        request.send();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                //document.querySelector('#cmb_punto').innerHTML = request.responseText;
                //document.querySelector('#cmb_punto').value = 0;
                $("#cmb_punto").html(request.responseText);
                $('#cmb_punto').val(0);
                $('#cmb_punto').selectpicker('refresh')
                //$('#cmb_punto').selectpicker('render');
            }
        }
    
    
}*/

//FUNCION PARA VISTA DE REGISTRO
function fntViewSec(ids){
    var ids = ids;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Secuencias/getSecuencia/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
               var estadoReg = objData.data.Estado == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_establecimiento").innerHTML = objData.data.Establecimiento;
                document.querySelector("#lbl_puntoEmi").innerHTML = objData.data.Punto;
                document.querySelector("#lbl_tipoDoc").innerHTML = objData.data.Tipo;
                document.querySelector("#lbl_secuencia").innerHTML = objData.data.Secuencia;
                document.querySelector("#lbl_nombre").innerHTML = objData.data.Documento;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng; 
                $('#modalViewSec').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}


//Editar Registro
function fntEditSec(ids){
    document.querySelector('#titleModal').innerHTML ="Actualizar Secuencia";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Secuencias/getSecuencia/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#txth_ids").value = objData.data.Ids;
                document.querySelector('#txt_sec_tipo').value=objData.data.Tipo;
                document.querySelector('#txt_sec_numero').value=objData.data.Numero;
                document.querySelector('#txt_sec_nombre').value=objData.data.Documento;
                document.querySelector("#cmb_establecimiento").value =objData.data.est_id;
                //$('#cmb_establecimiento').selectpicker('render');
                //fntPunto();-
                //document.querySelector('#cmb_punto').value=objData.data.pemi_id;
                //$('#cmb_punto').selectpicker('render');

                if(objData.data.Estado == 1){
                    document.querySelector("#cmb_estado").value = 1;
                }else{
                    document.querySelector("#cmb_estado").value = 2;
                }
                //$('#cmb_estado').selectpicker('render');
            }
        }
        $('#modalFormSec').modal('show');
    }
}

function fntDeleteSec(ids){
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
    }, function(isConfirm) {
        
        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Secuencias/delSecuencia';
            var strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar!", objData.msg , "success");
                        tableSec.api().ajax.reload(function(){
                            
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}



