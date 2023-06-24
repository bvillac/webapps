
let tableProductosBuscar;
let tableProveedorBuscar;
let tableProductos;
let tableOrdenes;
let rowTable = "";
//Opciones para Index

function codigoExiste(value, property, lista) {
    if (lista) {
        var array = JSON.parse(lista);
        for (var i = 0; i < array.length; i++) {
            if (array[i][property] == value) {
                return false;
            }
        }
    }
    return true;
}

function retornarIndexArray(array, property, value) {
    var index = -1;
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            index = i;
            return index;
        }
    }
    return index;
}

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}


$(document).ready(function() {
    //Nueva Orden
    $("#btnNewOrden").click(function() {
        eliminarStores();
        window.location = base_url+'/Orden/ordenCompra'; ;//Retorna al Portal Principal
    });
    //Buscar Proveedor
    $("#txtCodproveedor").keyup(function(e) {
        e.preventDefault();
        let codCli =$(this).val();
        if(codCli.length >= 4 && codCli!="" ){
            buscarProveedor(codCli);
        }
        
    });
    //Buscar Producto
    $("#txtCodigoItem").keyup(function(e) {
        e.preventDefault();
        let codigo =$(this).val();
        if(codigo.length >= 4 && codigo!=""){
            buscarItemProducto(codigo);
        }
        
    });

    //Calcular Total
    $("#txtCantidadItem").keyup(function(e) {
        e.preventDefault();
        let cantidad =$(this).val();
        let total=0;
        if(cantidad >0 && cantidad!=""){
            let precio=$('#txtPrecioItem').val();  
            //let nIva=$('#txthCargaIva').val();         
            total=calcularTotalItem(cantidad,precio);
            $('#txtTotalItem').val(redondea(total,N2decimal))
        }else{
            $('#txtTotalItem').val(redondea(total,N2decimal))
        }
        validarAgrega(cantidad);
        
    });

    $("#txtPrecioItem").keyup(function(e) {
        e.preventDefault();
        let precio =$(this).val();
        let total=0;
        if(precio >0 && precio!=""){
            let cantidad=$('#txtCantidadItem').val();            
            total=calcularTotalItem(cantidad,precio);
            $('#txtTotalItem').val(redondea(total,N2decimal))
        }else{
            $('#txtTotalItem').val(redondea(total,N2decimal))
        }
        validarAgrega(precio);
    });

    $("#cmd_agregar").click(function(e) {
        e.preventDefault();
        agregarItemsDoc();
        recalculaTotal();
    });

    $("#cmd_guardar").click(function(e) {
        e.preventDefault(); 
        if($('#txtCodproveedor').val() !="" && parseFloat($('#lbl_Total').text())>0 ){
            recalculaTotal();
            guardarOrden();
        }else{
            swal("Atención!", "No Existen Datos de Proveedor o Detalle" , "error");
        }     
        
    });
    

    $("#txtPrecioItem").blur(function(e) {
        e.preventDefault();
        let nPrecio =$(this).val()
        document.querySelector('#txtPrecioItem').value=redondea(nPrecio, N4decimal);
        recalculaTotal();
    });
    /*$("#txtCantidadItem").blur(function(e) {
        e.preventDefault();
        let nPrecio =$(this).val()
        document.querySelector('#txtCantidadItem').value=redondea(nPrecio, N4decimal);
        recalculaTotal();
    });*/


});


function calcularTotalItem(cantidad,precio){
    //PorIva 
    return cantidad*precio;
}

function validarAgrega(valor){
    if(valor<1 || isNaN(valor)){
        $('#cmd_agregar').slideUp();
    }else{
        $('#cmd_agregar').slideDown();
    }
}




function buscarProveedor(codigoProveedor){
    let link = base_url + '/Orden/getProveedor';
    var arrParams = new Object();
    arrParams.codigo = codigoProveedor;
    arrParams.ACCION = "Buscar";
    $.ajax({
        type: 'POST',
        url: link,
        data:{
            "codigo": codigoProveedor,
            //"acc": arrParams,
        } ,
        success: function(data){
            if (data.status){
                $('#txtCodproveedor').val(data.data[0]['pro_codigo']);
                /*$('#txtNomproveedor').val(data.data[0]['pro_nombre']);
                $('#txtRuc').val(data.data[0]['pro_cedula_ruc']);
                $('#txtDireccion').val(data.data[0]['pro_direccion']);*/
                $('#lbl_Ruc').text(data.data[0]['pro_cedula_ruc']);
                $('#lbl_Nombre').text(data.data[0]['pro_nombre']);
                $('#lbl_Direccion').text(data.data[0]['pro_direccion']);
            }else{
                limpiarProveedor();
                //swal("Atención!", "No Existen Datos" , "error");
            }
            $('#modalViewProveedor').modal('hide')
        },
        dataType: "json"
    });
}

function limpiarProveedor(){
    /*$('#txtNomproveedor').val("");
    $('#txtFechaIng').val("");
    $('#txtRuc').val("");
    $('#txtDireccion').val("");*/
    $('#txtCodproveedor').val("");
    $('#lbl_Ruc').text("");
    $('#lbl_Nombre').text("");
    $('#lbl_Direccion').text("");
}

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
                $('#txtStockItem').val(data.data['Stock']);
                $('#txtPrecioItem').val(data.data['Precio']);
                $('#txthCargaIva').val(data.data['Iva']);
                $('#txtCantidadItem').removeAttr("disabled");
                $('#txtPrecioItem').removeAttr("disabled");
                
            }else{
                limpiarProducto();
                $('#txtPrecioItem').attr("disabled","disabled");
                $('#txtCantidadItem').attr("disabled","disabled");
                //swal("Atención!", "No Existen Datos" , "error");
            }
            $('#modalViewProducto').modal('hide')
        },
        dataType: "json"
    });
}

function limpiarProducto(){
    //$('#txtCodigoItem').val("");
    $('#txtDetalleItem').val("");
    $('#txtCantidadItem').val("0");
    $('#txtStockItem').val("0");
    $('#txtPrecioItem').val("0.0000");
    $('#txtTotalItem').val("0.00");
}

function agregarItemsDoc() {
    let opAccion=($('#cmd_agregar').html()=="Agregar")?'new':'edit';
    var tGrid = 'TbG_tableItem';
    var nombre = $('#txtDetalleItem').val();
    if ($('#txtDetalleItem').val() != "") {
        var valor = $('#txtDetalleItem').val();
        if (opAccion != "edit") {
            //*********   AGREGAR ITEMS *********
            var arr_Grid = new Array();
            if (sessionStorage.dts_detalleData) {
                /*Agrego a la Sesion*/
                arr_Grid = JSON.parse(sessionStorage.dts_detalleData);
                var size = arr_Grid.length;
                if (size > 0) {
                    //Varios Items
                    if (codigoExiste(nombre, 'DetalleItem', sessionStorage.dts_detalleData)) {//Verifico si el Codigo Existe  para no Dejar ingresar Repetidos
                        arr_Grid[size] = objDataRow();
                        sessionStorage.dts_detalleData = JSON.stringify(arr_Grid);
                        addVariosItem(tGrid, arr_Grid, -1);
                        limpiarTexbox();
                    } else {
                        swal("Atención!", "Item ya existe en su lista" , "error");
                    }
                } else {
                    /*Agrego a la Sesion*/
                    //Primer Items
                    arr_Grid[0] = objDataRow();
                    sessionStorage.dts_detalleData = JSON.stringify(arr_Grid);
                    addPrimerItem(tGrid, arr_Grid, 0);
                    limpiarTexbox();
                }
            } else {
                //No existe la Session
                //Primer Items
                arr_Grid[0] = objDataRow();
                sessionStorage.dts_detalleData = JSON.stringify(arr_Grid);
                addPrimerItem(tGrid, arr_Grid, 0);
                limpiarTexbox();
            }

        } else {
            //Actualizar Items
            $('#cmd_agregar').html("Agregar");
            actualizarItemsDetalle(nombre,tGrid);
            limpiarTexbox();
        }
    } else {
        swal("Atención!", "No Existen Información" , "error");
    }
}

function limpiarTexbox(){
    $('#txtCodigoItem').val("");
    $('#txtDetalleItem').val("");
    $('#txtCantidadItem').val("0");
    $('#txtStockItem').val("0");
    $('#txtPrecioItem').val("0.00");
    $('#txtTotalItem').val("0.00");
    $('#txthCargaIva').val("0");
}

function objDataRow() {
    rowGrid = new Object();
    rowGrid.CodigoItem =$('#txtCodigoItem').val();
    rowGrid.DetalleItem =$('#txtDetalleItem').val();
    rowGrid.CantidadItem =$('#txtCantidadItem').val();
    rowGrid.StockItem =$('#txtStockItem').val();
    rowGrid.PrecioItem =$('#txtPrecioItem').val();
    rowGrid.TotalItem =$('#txtTotalItem').val();
    rowGrid.cargaIva =$('#txthCargaIva').val();
    return rowGrid;
}


function addPrimerItem(TbGtable, lista, i) {
    /*Remuevo la Primera fila*/
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable).append(retornaFilaData(i, lista, TbGtable, true));
}

function addVariosItem(TbGtable, lista, i) {
    i = (i == -1) ? ($('#' + TbGtable + ' tr').length) - 1 : i;
    $('#' + TbGtable).append(retornaFilaData(i, lista, TbGtable, true));
}

function retornaFilaData(c, Grid, TbGtable, op) {
    var strFila = "";
    strFila += '<td>' + Grid[c]['CodigoItem'] + '</td>';
    strFila += '<td colspan="2">' + Grid[c]['DetalleItem'] + '</td>';
    strFila += '<td>' + Grid[c]['CantidadItem'] + '</td>';
    strFila += '<td class="textright">' + Grid[c]['PrecioItem'] + '</td>';
    strFila += '<td class="textright">' + Grid[c]['TotalItem'] + '</td>';
    strFila += '<td>';
        strFila += ' <a href="#" class="link_delete" onclick="event.preventDefault();editarItemsDetalle(\'' + Grid[c]['CodigoItem'] + '\',\'' + TbGtable + '\');"><i class="fa fa-pencil"></i></a>';
        strFila += ' <a href="#" class="link_delete" onclick="event.preventDefault();eliminarItemsDetalle(\'' + Grid[c]['CodigoItem'] + '\',\'' + TbGtable + '\');"><i class="fa fa-trash"></i></a>';
    strFila += '</td>';
    if (op) {
        strFila = '<tr class="odd gradeX">' + strFila + '</tr>';
    }
    return strFila;
}

function recargarGridDetalle() {
    var tGrid = 'TbG_tableItem';
    if (sessionStorage.dts_detalleData) {
        var arr_Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' >table >tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid).append(retornaFilaData(i, arr_Grid, tGrid, true));
            }
            recalculaTotal();
        }
    }
}


function eliminarItemsDetalle(codigo, TbGtable) {
    let ids = "";
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == codigo) {
                    var array = findAndRemove(Grid, 'CodigoItem', ids);
                    sessionStorage.dts_detalleData = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')} 
                    $(this).remove();
                }
            });
        }
    }
    recalculaTotal();
}

function editarItemsDetalle(codigo, TbGtable) {
    let ids = "";
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == codigo) {
                    $('#txtCodigoItem').val($(this).find("td").eq(0).html());
                    $('#txtDetalleItem').val($(this).find("td").eq(1).html());
                    $('#txtCantidadItem').val($(this).find("td").eq(2).html());                    
                    $('#txtPrecioItem').val($(this).find("td").eq(3).html());
                    $('#txtTotalItem').val($(this).find("td").eq(4).html());
                    $('#cmd_agregar').html("Modificar");
                }
            });
        }
    }
}

function actualizarItemsDetalle(codigo, TbGtable) {
    let ids = "";
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            let index = retornarIndexArray(Grid,'DetalleItem',codigo);//Obtener Indice por detalle
            if(index>-1){
                Grid[index]['CantidadItem']=$('#txtCantidadItem').val();
                Grid[index]['PrecioItem']=$('#txtPrecioItem').val();
                Grid[index]['TotalItem']=$('#txtTotalItem').val();
                sessionStorage.dts_detalleData = JSON.stringify(Grid);
                $('#' + TbGtable + ' tr').each(function () {
                    ids = $(this).find("td").eq(0).html();
                    if (ids == Grid[index]['CodigoItem']) {
                        $(this).find("td").eq(2).html(Grid[index]['CantidadItem']);
                        $(this).find("td").eq(3).html(Grid[index]['PrecioItem']);
                        $(this).find("td").eq(4).html(Grid[index]['TotalItem']);
                    }
                });
            }
        }
    }
    recalculaTotal();
}


function recalculaTotal() {
    let subTotal=0;
    let subTotalIva0=0;
    let subTotalIva12=0;
    let Impuesto=0;
    let Total=0;
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {
                subTotal=parseInt(Grid[i]['CantidadItem'])*parseFloat(Grid[i]['PrecioItem']);
                if(Grid[i]['cargaIva']==1){
                    subTotalIva12 +=subTotal;
                }else{
                    subTotalIva0 +=subTotal;
                }
            }
            $('#lbl_SubTotal').text(redondea(subTotalIva0+subTotalIva12, N2decimal));
            $('#lbl_Base0').text(redondea(subTotalIva0, N2decimal));
            $('#lbl_Base12').text(redondea(subTotalIva12, N2decimal));
            Impuesto=subTotalIva12*(PorIva/100);
            $('#lbl_Iva').text(redondea(Impuesto, N2decimal));
            Total=subTotalIva0+subTotalIva12+Impuesto;
            $('#lbl_Total').text(redondea(Total, N2decimal));
        }else{
            $('#lbl_SubTotal').text(redondea(0, N2decimal));
            $('#lbl_Base0').text(redondea(0, N2decimal));
            $('#lbl_Base12').text(redondea(0, N2decimal));
            $('#lbl_Iva').text(redondea(0, N2decimal));
            $('#lbl_Total').text(redondea(0, N2decimal));
        }
    }else{
        $('#lbl_SubTotal').text(redondea(0, N2decimal));
        $('#lbl_Base0').text(redondea(0, N2decimal));
        $('#lbl_Base12').text(redondea(0, N2decimal));
        $('#lbl_Iva').text(redondea(0, N2decimal));
        $('#lbl_Total').text(redondea(0, N2decimal));
    }


}

function openModalBuscar(){   
    rowTable = "";
    mostrarListaItems();
    //document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#titleModal').innerHTML = "Buscar Item";
    //document.querySelector("#formProductos").reset();
    $('#modalViewProducto').modal('show');
}
function openModalProveedorBuscar(){   
    rowTable = "";
    mostrarListaProveedor();
    //document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#titleModalProveedor').innerHTML = "Buscar Proveedor";
    //document.querySelector("#formProductos").reset();
    $('#modalViewProveedor').modal('show');
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

function mostrarListaProveedor(){   
    tableProveedorBuscar = $('#tableProveedor').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": cdnTable
        },
        "ajax":{
            "url": " "+base_url+"/Proveedor/getProveedorbuscar",
            "dataSrc":""
        },
        "columns":[
            {"data":"Ids"},
            {"data":"Nombre"},
            {"data":"Cedula"},
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
}


/**************** GUARDAR DATOS PEDIDOS  ******************/
function guardarOrden() {
    let accion=($('#cmd_guardar').html()=="Guardar")?'Create':'edit';
    var total = parseFloat($('#lbl_Total').text());
    if ($('#txtCodproveedor').val() != "" && total > 0) {      
            $("#cmd_guardar").attr('disabled', true);
            //var ID = (accion == "edit") ? $('#txth_PedID').val() : 0;
            let link = base_url + '/Orden/ingresarOrden';
            $.ajax({
                type: 'POST',
                url: link,
                data: {
                    //"dts_detalle": (accion == "Create") ? listaDetalle() : listaPedidoDetTemp(),
                    "cabecera": listaCabecera(),
                    "dts_detalle": listaDetalle(),
                    "accion": accion
                },
                success: function (data) {
                    console.log("resp "+ data.status);
                    if (data.status) {
                        sessionStorage.removeItem('cabeceraOrden');
                        sessionStorage.removeItem('dts_detalleData');
                        swal("Orden Compras", data.msg ,"success");
                        window.location = base_url+'/Orden/orden'; 
                        
                    } else {
                        swal("Error", data.msg , "error");
                    }
                },
                dataType: "json"
            });
   

    } else {
        swal("Atención!", "No Existen datos para Guardar" , "error");
    }
}


function listaCabecera(){
    var cabecera=new Object();
    cabecera.Codproveedor=$('#txtCodproveedor').val();
    cabecera.SecId=2;
    cabecera.Tipo="OC";
    cabecera.Numero=$('#lbl_Secuencia').text();
    cabecera.ValorBruto=$('#lbl_SubTotal').text();
    cabecera.ValorBase0=$('#lbl_Base0').text();
    cabecera.ValorBase12=$('#lbl_Base12').text();
    cabecera.ValorIva=$('#lbl_Iva').text();
    cabecera.ValorNeto=$('#lbl_Total').text();    
    cabecera.estado='1';
    sessionStorage.cabeceraOrden = JSON.stringify(cabecera);
    //return JSON.stringify(JSON.stringify(cabecera));
    return cabecera;
}

function listaDetalle() {
    var arrayList = new Array;
    var c=0;
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {                
                if(parseFloat(Grid[i]['CantidadItem'])>0){
                    let rowGrid = new Object();
                    rowGrid.CodigoItem = Grid[i]['CodigoItem'];
                    rowGrid.DetalleItem = Grid[i]['DetalleItem'];
                    rowGrid.CantidadItem = Grid[i]['CantidadItem'];
                    rowGrid.StockItem = Grid[i]['StockItem'];
                    rowGrid.PrecioItem = Grid[i]['PrecioItem'];
                    rowGrid.TotalItem = Grid[i]['TotalItem'];
                    rowGrid.cargaIva = Grid[i]['cargaIva'];
                    arrayList[c] = rowGrid;
                    c += 1;
                }
            }    
        }
    }
    //return JSON.stringify(arrayList);
    return arrayList;
}


tableOrdenes = $('#tableOrdenes').dataTable( {
    "aProcessing":true,
    "aServerSide":true,
    "language": {
        "url": cdnTable
    },
    "ajax":{
        "url": " "+base_url+"/Orden/getOrdenes",
        "dataSrc":""
    },
    "columns":[
        {"data":"Norden"},
        {"data":"Fecha"},
        {"data":"Ncompra"},
        {"data":"Nombre"},
        {"data":"Vneto"},
        {"data":"Autorizado"},
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


function fntAnular(ids){
    swal({
        title: "Anular",
        text: "¿Realmente quiere Anular Orden?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Anular!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Orden/anularOrden';
            let strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Anulado!", objData.msg , "success");
                        tableOrdenes.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}


function fntAutoriza(ids){
    swal({
        title: "Autorizar",
        text: "¿Realmente quiere Autorizar Orden?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Autorizar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Orden/autorizarOrden';
            let strData = "ids="+ids;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Autorizdo!", objData.msg , "success");
                        tableOrdenes.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}


function eliminarStores(){
    sessionStorage.removeItem('cabeceraOrden');
    sessionStorage.removeItem('dts_detalleData');
}


window.addEventListener('load', function() {
    recargarGridDetalle();
}, false);