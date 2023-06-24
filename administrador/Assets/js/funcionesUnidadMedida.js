var tableUnidadMedida;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function(){
	tableMoneda = $('#tableUnidadMedida').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
            "url": cdnTable       
        },
        "ajax":{
            "url": " "+base_url+"/UnidadMedida/getUnidadMedidas",
            "dataSrc":""
        },
        "columns":[
            {"data":"Ids"},
            {"data":"Nombre"},
            {"data":"Nomenclatura"},
            {"data":"FactorConversion"},
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
    var formUnidadMedida = document.querySelector("#formUnidadMedida");//Nombre del formulario 
    formUnidadMedida.onsubmit = function(e) {//Se ejecuta en el Summit
        e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
        //Captura de Campos
        var Ids = document.querySelector('#txth_ids').value;
        var umed_nombre = document.querySelector('#txt_umed_nombre').value;
        var umed_nomenclatura = document.querySelector('#txt_umed_nomenclatura').value;
        var umed_factor_conversion = document.querySelector('#txt_umed_factor_conversion').value;
        var estado = document.querySelector('#cmb_estado').value;       
        if(umed_nombre == '' || umed_nomenclatura == '' || umed_factor_conversion == '' || estado == '')//Validacin de Campos
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
        var ajaxUrl = base_url+'/UnidadMedida/setUnidadMedida'; 
        var formData = new FormData(formUnidadMedida);//Objeto de Formulario capturado
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){//Responde  
                console.log(request.responseText); //Ver el Retorno             
                var objData = JSON.parse(request.responseText);//Casting Object
                if(objData.status) {
                    $('#modalFormUnidadMedida').modal("hide");//Oculta el Modal
                    formMoneda.reset();//Limpiar los campos del formulario
                    swal("UnidadMedidas", objData.msg ,"success");
                    tableUnidadMedida.api().ajax.reload(function(){//Actualizar o refrescar el Datatable de ROL 
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
    document.querySelector('#titleModal').innerHTML = "Nueva Unidad de Medida";
    document.querySelector("#formUnidadMedida").reset();
	$('#modalFormUnidadMedida').modal('show');
}



//FUNCION PARA VISTA DE REGISTRO
function fntViewUnidadMedida(ids){
    var ids = ids;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/UnidadMedida/getUnidadMedida/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
               var estadoReg = objData.data.Estado == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_nom_umed").innerHTML = objData.data.Nombre;
                document.querySelector("#lbl_nomen_umed").innerHTML = objData.data.Nomenclatura;
                document.querySelector("#lbl_faconv_umed").innerHTML = objData.data.FactorConversion;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng; 
                $('#modalViewUnidadMedida').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}



//Editar Registro
function fntEditUnidadMedida(ids){
    document.querySelector('#titleModal').innerHTML ="Actualizar Unidad de Medida";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/UnidadMedida/getUnidadMedida/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#txth_ids").value = objData.data.Ids;
                document.querySelector("#txt_umed_nombre").value = objData.data.Nombre;
                document.querySelector("#txt_umed_nomenclatura").value = objData.data.Nomenclatura;
                document.querySelector("#txt_umed_factor_conversion").value = objData.data.FactorConversion;
                if(objData.data.Estado == 1){
                    document.querySelector("#cmb_estado").value = 1;
                }else{
                    document.querySelector("#cmb_estado").value = 2;
                }
                $('#cmb_estado').selectpicker('render');
            }
        }
        $('#modalFormUnidadMedida').modal('show');
    }
}

function fntDeleteUnidadMedida(ids){
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
            var ajaxUrl = base_url+'/UnidadMedida/delUnidadMedida';
            var strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar!", objData.msg , "success");
                        tableUnidadMedida.api().ajax.reload(function(){
                            
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}



