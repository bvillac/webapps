var tableBodega;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function(){
	tableBodega = $('#tableBodega').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
            "url": cdnTable       
        },
        "ajax":{
            "url": " "+base_url+"/Bodega/getBodegas",
            "dataSrc":""
        },
        "columns":[
            {"data":"Ids"},
            {"data":"Nombre"},
            {"data":"Direccion"},
            {"data":"Telefono"},
            {"data":"Estado"},
            {"data":"options"}
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

        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order":[[0,"desc"]]  //Orden por defecto 1 columna
    });

    //NUEVO
    var formBodega = document.querySelector("#formBodega");//Nombre del formulario 
    formBodega.onsubmit = function(e) {//Se ejecuta en el Summit
        e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
        //Captura de Campos
        var Ids = document.querySelector('#txth_ids').value;
        var bod_nombre = document.querySelector('#txt_bod_nombre').value;
        var bod_direccion = document.querySelector('#txt_bod_direccion').value;
        var bod_telefono = document.querySelector('#txt_bod_telefono').value;
        var estado = document.querySelector('#cmb_estado').value;       
        if(bod_nombre == '' || bod_direccion == '' || bod_telefono == '' || estado == '')//Validacin de Campos
          {
              swal("Atención", "Todos los campos son obligatorios." , "error");
              return false;
          }
           //Verificas los elementos conl clase valid para controlar que esten ingresados
        let elementsValid = document.getElementsByClassName("valid");
        for (let i = 0; i < elementsValid.length; i++) { 
            if(elementsValid[i].classList.contains('is-invalid')) { 
                swal("Atención", "Por favor verifique los campos ingresados (Color Rojo)." , "error");
                return false;
            } 
        } 
        //Variable Request para los navegadores segun el Navegador (egde,firefox,chrome)
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Bodega/setBodega'; 
        var formData = new FormData(formBodega);//Objeto de Formulario capturado
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){//Responde  
                console.log(request.responseText); //Ver el Retorno             
                var objData = JSON.parse(request.responseText);//Casting Object
                if(objData.status) {
                    $('#modalFormBodega').modal("hide");//Oculta el Modal
                    formBodega.reset();//Limpiar los campos del formulario
                    swal("Bodegas", objData.msg ,"success");
                    tableBodega.api().ajax.reload(function(){//Actualizar o refrescar el Datatable de ROL 
                        //Asignar evetos para que no se pierdasn
                       
                    });
                }else{
                    swal("Error", objData.msg , "error");
                }              
            } 
        }

        
    }

  

});


function openModal(){
    document.querySelector('#txth_ids').value ="";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Bodega";
    document.querySelector("#formBodega").reset();
	$('#modalFormBodega').modal('show');
}



//FUNCION PARA VISTA DE REGISTRO
function fntViewBodega(ids){
    var ids = ids;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Bodega/getBodega/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
               var estadoReg = objData.data.Estado == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_nom_bodega").innerHTML = objData.data.Nombre;
                document.querySelector("#lbl_dir_bodega").innerHTML = objData.data.Direccion;
                document.querySelector("#lbl_tel_bodega").innerHTML = objData.data.Telefono;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng; 
                $('#modalViewBodega').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}



//Editar Registro
function fntEditBodega(ids){
    document.querySelector('#titleModal').innerHTML ="Actualizar Bodega";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Bodega/getBodega/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#txth_ids").value = objData.data.Ids;
                document.querySelector("#txt_bod_nombre").value = objData.data.Nombre;
                document.querySelector("#txt_bod_direccion").value = objData.data.Direccion;
                document.querySelector("#txt_bod_telefono").value = objData.data.Telefono;
                if(objData.data.Estado == 1){
                    document.querySelector("#cmb_estado").value = 1;
                }else{
                    document.querySelector("#cmb_estado").value = 2;
                }
                $('#cmb_estado').selectpicker('render');
            }
        }
        $('#modalFormBodega').modal('show');
    }
}

function fntDeleteBodega(ids){
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
            var ajaxUrl = base_url+'/Bodega/delBodega';
            var strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar!", objData.msg , "success");
                        tableBodega.api().ajax.reload(function(){
                            
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}



