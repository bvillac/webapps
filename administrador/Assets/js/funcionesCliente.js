//Integrar la libreria Cedula Ruc
document.write(`<script src="${base_url}/Assets/js/cedulaRucPass.js"></script>`);//
var tableCliente;

//Cuando se cargue todo ejecuta las funciones
document.addEventListener('DOMContentLoaded', function(){
	tableCliente = $('#tableCliente').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
            "url": cdnTable       
        },
        "ajax":{
            "url": " "+base_url+"/Cliente/getClientes",
            "dataSrc":""
        },
        "columns":[
            //{"data":"Ids"},
            //{"data":"Tipo"},
            {"data":"Cedula"},
            {"data":"Nombre"},
            {"data":"Direccion"},
            {"data":"Correo"},
            {"data":"Telefono"},
            {"data":"Distribuidor"},
            {"data":"Precio"},
            //{"data":"Certificado"},
            {"data":"Pago"},
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
        "order":[[0,"asc"]]  //Orden por defecto 1 columna
    });

    //NUEVO
    var formCliente = document.querySelector("#formCliente");//Nombre del formulario 
    formCliente.onsubmit = function(e) {//Se ejecuta en el Summit
        e.preventDefault();//Parar el envio de datos y que se resfresque la pagina
        //Captura de Campos
        var Ids = document.querySelector('#txth_ids').value;
        var codigo = document.querySelector('#txt_codigo').value; 
        var tipo = document.querySelector('#txt_cli_tipo_dni').value; 
        var cedula = document.querySelector('#txt_cli_cedula_ruc').value;
        var nombre = document.querySelector('#txt_cli_nombre').value;
        var direccion = document.querySelector('#txt_cli_direccion').value;
        var correo = document.querySelector('#txt_cli_correo').value;
        var telefono = document.querySelector('#txt_cli_telefono').value;
        var cli_distribuidor = document.querySelector('#cmb_distribuidor').value;
        var cli_tipo_precio = document.querySelector('#cmb_tipo_precio').value;
        var url = '';//document.querySelector('#txt_ruta_certificado').value;
        var pago = document.querySelector('#cmb_pago').value;
        var estado = document.querySelector('#cmb_estado').value;        
        if(codigo == '' || tipo == '' || cedula == '' || nombre == '' || direccion == '' || correo == '' || telefono == '' || cli_distribuidor == '' || cli_tipo_precio == ''  || pago == '' || estado == ''){//Validacin de Campos
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
        var ajaxUrl = base_url+'/Cliente/setCliente'; 
        var formData = new FormData(formCliente);//Objeto de Formulario capturado
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){//Responde  
                console.log(request.responseText); //Ver el Retorno             
                var objData = JSON.parse(request.responseText);//Casting Object
                if(objData.status) {
                    $('#modalFormCliente').modal("hide");//Oculta el Modal
                    formCliente.reset();//Limpiar los campos del formulario
                    swal("Clientes", objData.msg ,"success");
                    tableCliente.api().ajax.reload(function(){//Actualizar o refrescar el Datatable de ROL 
                        
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
    document.querySelector('#titleModal').innerHTML = "Cliente";
    document.querySelector("#formCliente").reset();
	$('#modalFormCliente').modal('show');
}

window.addEventListener('load', function() {
    fntPago();
}, false);

$(document).ready(function() {
    $("#txt_cli_cedula_ruc").blur(function() {        
        let valor = document.querySelector('#txt_cli_cedula_ruc').value;
        if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }
    });

    $('#txt_cli_tipo_dni').change(function () {    
        let op = document.querySelector('#txt_cli_tipo_dni').value;     
        switch(op){
            case "01": 
                $("#txt_cli_cedula_ruc").attr("maxlength", 10); 
                break;
            case "02":
                $("#txt_cli_cedula_ruc").attr("maxlength", 13);
                break;
            default:
                $("#txt_cli_cedula_ruc").attr("maxlength", 15);
                break;
        } 
        
    });


   
});

function fntPago(){
    var ajaxUrl = base_url+'/Cliente/getPago';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            document.querySelector('#cmb_pago').innerHTML = request.responseText;
            document.querySelector('#cmb_pago').value = 0;
            $('#cmb_pago').selectpicker('render');
        }
    }
    
}

//FUNCION PARA VISTA DE REGISTRO
function fntViewCliente(ids){
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Cliente/getCliente/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
               var estadoReg = objData.data.Estado == 1 ? 
                '<span class="badge badge-success">Activo</span>' : 
                '<span class="badge badge-danger">Inactivo</span>';
                document.querySelector("#lbl_ids").innerHTML = objData.data.Ids;
                document.querySelector("#lbl_tipo").innerHTML = objData.data.Tipo;
                document.querySelector("#lbl_cedula").innerHTML = objData.data.Cedula;
                document.querySelector("#lbl_nombre").innerHTML = objData.data.Nombre;
                document.querySelector("#lbl_direccion").innerHTML = objData.data.Direccion;
                document.querySelector("#lbl_correo").innerHTML = objData.data.Correo;
                document.querySelector("#lbl_telefono").innerHTML = objData.data.Telefono;
                document.querySelector("#lbl_distribuidor").innerHTML = objData.data.Distribuidor;
                document.querySelector("#lbl_tipo_precio").innerHTML = objData.data.Precio;
                //document.querySelector("#lbl_certificado").innerHTML = objData.data.Certificado;
                document.querySelector("#lbl_pago").innerHTML = objData.data.Pago;
                document.querySelector("#lbl_estado").innerHTML = estadoReg;
                document.querySelector("#lbl_fecIng").innerHTML = objData.data.FechaIng; 
                $('#modalViewCliente').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}


//Editar Registro
function fntEditCliente(ids){
    document.querySelector('#titleModal').innerHTML ="Actualizar Cliente";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Cliente/getCliente/'+ids;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#txth_ids").value = objData.data.Ids;
                document.querySelector('#txt_codigo').value=objData.data.Ids;
                document.querySelector('#txt_cli_tipo_dni').value=objData.data.Tipo;
                document.querySelector('#txt_cli_cedula_ruc').value=objData.data.Cedula;
                document.querySelector('#txt_cli_nombre').value=objData.data.Nombre;
                document.querySelector('#txt_cli_direccion').value=objData.data.Direccion;
                document.querySelector('#txt_cli_correo').value=objData.data.Correo;
                document.querySelector('#txt_cli_telefono').value=objData.data.Telefono;
                document.querySelector('#cmb_distribuidor').value=objData.data.Distribuidor;
                document.querySelector('#cmb_tipo_precio').value=objData.data.Precio;
                //document.querySelector('#txt_ruta_certificado').value=objData.data.Certificado;
                document.querySelector("#cmb_pago").value =objData.data.fpag_id;
                $('#cmb_pago').selectpicker('render');
                if(objData.data.Estado == 1){
                    document.querySelector("#cmb_estado").value = 1;
                }else{
                    document.querySelector("#cmb_estado").value = 2;
                }
                $('#cmb_estado').selectpicker('render');
                if(objData.data.Distribuidor == 1){
                    document.querySelector("#cmb_distribuidor").value = 1;
                }else{
                    document.querySelector("#cmb_distribuidor").value = 0;
                }
                $('#cmb_distribuidor').selectpicker('render');

              
            }
        }
        $('#modalFormCliente').modal('show');
    }
}


function fntDeleteCliente(ids){
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
            var ajaxUrl = base_url+'/Cliente/delCliente';
            var strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar!", objData.msg , "success");
                        tableCliente.api().ajax.reload(function(){
                            
                        });
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}