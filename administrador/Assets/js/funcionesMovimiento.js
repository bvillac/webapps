let tableProductos;
let tableProductosBuscar;


$(document).ready(function() {
 
   
    //Buscar Producto
    $("#txtCodigoItem").keyup(function(e) {
        e.preventDefault();
        let codigo =$(this).val();
        if(codigo.length >= 4 && codigo!=""){
            buscarItemProducto(codigo);
        }
        
    });

    $("#cmd_buscar").click(function(e) {
        e.preventDefault();
        buscarMovimiento();
    });

});

function buscarItemProducto(codigo){
    let link = base_url + '/Orden/getItemProducto';
    $.ajax({
        type: 'POST',
        url: link,
        data:{
            "codigo": codigo,
        } ,
        success: function(data){
            if (data.status){//Iva
                $('#txtCodigoItem').val(data.data['Codigo']);
                $('#txtDetalleItem').val(data.data['Nombre']);
               
                //$('#txtCantidadItem').removeAttr("disabled");
                //$('#txtPrecioItem').removeAttr("disabled");
                
            }else{
                //limpiarProducto();
                //$('#txtPrecioItem').attr("disabled","disabled");
                //$('#txtCantidadItem').attr("disabled","disabled");
                //swal("Atención!", "No Existen Datos" , "error");
            }
            $('#modalViewProducto').modal('hide')
        },
        dataType: "json"
    });
}

function fntBodegas(){
    if(document.querySelector('#cmb_bodega')){
        let ajaxUrl = base_url+'/Items/getBodegas';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#cmb_bodega').innerHTML = request.responseText;
                $('#cmb_bodega').selectpicker('render');
            }
        }
    }
}



function mostrarListaItems(){       
    tableProductosBuscar = $('#tableItem').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": cdnTable
        },
        "ajax":{
            "url": " "+base_url+"/Items/getItemsbuscar",
            "dataSrc":""
        },
        "columns":[
            {"data":"Codigo"},
            {"data":"Nombre"},
            {"data":"Stock"},
            {"data":"P_Costo"},
            {"data":"options"}
        ],
        "columnDefs": [
            { 'className': "textcenter", "targets": [ 3 ] },//Agregamos la clase que va a tener la columna
            { 'className': "textright", "targets": [ 4 ] },
        // { 'className': "textcenter", "targets": [ 5 ] }
                    ],       
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

}


function openModalBuscar(){   
    rowTable = "";
    mostrarListaItems();
    //document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#titleModal').innerHTML = "Buscar Item";
    //document.querySelector("#formProductos").reset();
    $('#modalViewProducto').modal('show');
}

function buscarMovimiento(){
    var tGrid = 'tableMovimiento';
    $('#'+ tGrid + ' > tbody').empty();
    let link = base_url + '/Movimiento/getMovimiento';
    $.ajax({
        type: 'POST',
        url: link,
        data:{
            "bodega": $('#cmb_bodega').val(),//document.querySelector('#cmb_bodega').value;
            "codigo": $('#txtCodigoItem').val(),
            "fecDes": "2021-01-01",//$('#txtCodigoItem').val(),
            "fecHas": "2021-12-01",//$('#txtCodigoItem').val(),
        } ,
        success: function(data){
            let dataMov=data['MOVIMIENTO'];
            if(dataMov.length){  
                $('#lbl_tIngreso').text(redondea(parseInt(data['TOT_ING']), N2decimal));  
                $('#lbl_tEgreso').text(redondea(parseInt(data['TOT_EGR']), N2decimal));  
                $('#lbl_tSaldo').text(redondea(parseInt(data['TOT_ING'])-parseInt(data['TOT_EGR']), N2decimal));  
                for (var c = 0; c < dataMov.length; c++) {                 
                    var strFila = "";            
                    strFila += '<td>' + dataMov[c]['FECHA'] + '</td>';
                    strFila += '<td>' + dataMov[c]['INGRESO'] + '</td>';
                    strFila += '<td>' + dataMov[c]['EGRESO'] + '</td>';
                    strFila += '<td class="textright">' + dataMov[c]['CANTIDAD'] + '</td>';
                    strFila += '<td class="textright">' + dataMov[c]['SALDO'] + '</td>';
                    strFila += '<td>' + dataMov[c]['ESTADO'] + '</td>';
                    strFila += '<td>' + dataMov[c]['REFERENCIA'] + '</td>';                  
                    strFila = '<tr class="odd gradeX">' + strFila + '</tr>';                   
                    $('#' + tGrid ).append(strFila);
                }

            }else{
                swal("Atención!", "No Existen Datos" , "error");
            }
        },
        dataType: "json"
    });
}






window.addEventListener('load', function() {
    fntBodegas();
}, false);
