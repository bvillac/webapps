
let tableCompras;
let tableDespacho;
let rowTable = "";

tableCompras = $('#tableCompras').dataTable( {
    "aProcessing":true,
    "aServerSide":true,
    "language": {
        "url": cdnTable
    },
    "ajax":{
        "url": " "+base_url+"/Venta/getVentas",
        "dataSrc":""
    },
    "columns":[
        {"data":"Numero"},        
        {"data":"Fecha"},
        {"data":"Cedula"},
        {"data":"Nombre"},
        {"data":"ValorNeto"},
        {"data":"FormaPago"},
        {"data":"Despacho"},
        {"data":"Estado"},
        {"data":"options"}
    ],
    "columnDefs": [
        //{ 'className': "textcenter", "targets": [ 3 ] },//Agregamos la clase que va a tener la columna
        //{ 'className': "textright", "targets": [ 4 ] },
       // { 'className': "textcenter", "targets": [ 5 ] }
                  ],       
    "resonsieve":"true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order":[[0,"desc"]]  
});


tableDespacho = $('#tableDespacho').dataTable( {
    "aProcessing":true,
    "aServerSide":true,
    "language": {
        "url": cdnTable
    },
    "ajax":{
        "url": " "+base_url+"/Venta/getVentasDespacho",
        "dataSrc":""
    },
    "columns":[
        {"data":"Numero"},        
        {"data":"Fecha"},
        {"data":"Cedula"},
        {"data":"Nombre"},
        {"data":"ValorNeto"},
        {"data":"FormaPago"},
        {"data":"Despacho"},
        {"data":"Estado"},
        {"data":"options"}
    ],
    "columnDefs": [
        //{ 'className': "textcenter", "targets": [ 3 ] },//Agregamos la clase que va a tener la columna
        //{ 'className': "textright", "targets": [ 4 ] },
       // { 'className': "textcenter", "targets": [ 5 ] }
                  ],       
    "resonsieve":"true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order":[[0,"desc"]]  
});


function fntDespachar(ids){
    swal({
        title: "Despachar",
        text: "¿Realmente quiere Despachar Documento?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Despachar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Venta/despacharDocumento';
            let strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Despachado!", objData.msg , "success");
                        tableDespacho.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}
