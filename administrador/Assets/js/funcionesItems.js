//Integrar la libreria barcode
document.write(`<script src="${base_url}/Assets/js/plugins/JsBarcode.all.min.js"></script>`);//Se agrega direcctamente el Plugin para que solo se cargue en esta vista y no utilice recursos en los otros

let tableProductos;
let rowTable = "";
//Funcion para dar el focus a los Modal cuando se abran algunos
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});



tableProductos = $('#tableItem').dataTable( {
    "aProcessing":true,
    "aServerSide":true,
    "language": {
        "url": cdnTable
    },
    "ajax":{
        "url": " "+base_url+"/Items/getItems",
        "dataSrc":""
    },
    "columns":[   
        {"data":"Codigo"},
        {"data":"Nombre"},
        {"data":"Linea"},
        {"data":"Stock"},
        {"data":"P_Costo"},
        {"data":"Estado"},
        {"data":"options"}
    ],
    "columnDefs": [
        //{ 'className': "textcenter", "targets": [ 3 ] },//Agregamos la clase que va a tener la columna
        //{ 'className': "textright", "targets": [ 4 ] },
        //{ 'className': "textcenter", "targets": [ 5 ] }
                  ],       
    'dom': 'lBfrtip',
    'buttons': [
       /* {
            "extend": "excelHtml5",
            "text": "<i class='fas fa-file-excel'></i> Excel",
            "titleAttr":"Esportar a Excel",
            "title":"REPORTE DE PRODUCTOS REGISTRADOS",
            "className": "btn btn-success",
            "exportOptions": { 
                "columns": [ 0, 1, 2, 3, 4, 5] //las columna a Exportar
            }
        },
        {
            "extend": "pdfHtml5",
            "text": "<i class='fas fa-file-pdf'></i> PDF",
            "titleAttr":"Esportar a PDF",
            "title":"REPORTE DE PRODUCTOS REGISTRADOS",
            "className": "btn btn-danger",
            "exportOptions": { 
                "columns": [ 0, 1, 2, 3, 4, 5] 
            }
        }*/
    ],
    "resonsieve":"true",
    "bDestroy": true,
    "iDisplayLength": 10,
    "order":[[0,"desc"]]  
});


window.addEventListener('load', function() {
    //Guardar Items
    if(document.querySelector("#formProductos")){
        let formProductos = document.querySelector("#formProductos");
        formProductos.onsubmit = function(e) {
            e.preventDefault();
            //Informacion
            let txtNombre = document.querySelector('#txtNombre').value;
            let txtCodigo = document.querySelector('#txtCodigo').value;
            let cmb_linea = document.querySelector('#cmb_linea').value;
            let lineaName=$('#cmb_linea').text();
            let cmb_tipo = document.querySelector('#cmb_tipo').value;
            let cmb_marca = document.querySelector('#cmb_marca').value;
            let cmb_medida = document.querySelector('#cmb_medida').value;
            let txtPercha = document.querySelector('#txtPercha').value;
            let txtUbicacion = document.querySelector('#txtUbicacion').value;
            let cmb_iva = document.querySelector('#cmb_iva').value;
            let cmb_estado = document.querySelector('#cmb_estado').value;
            //let cmb_bodega = 1;//Bodega por Defecto//document.querySelector('#cmb_bodega').value;
            //Precios
            let txtLista = document.querySelector('#txtLista').value;
            let txtPromedio = document.querySelector('#txtPromedio').value;
            let txtPorCostos = document.querySelector('#txtPorCostos').value;
            let txtCostos = document.querySelector('#txtCostos').value;
            let txtPor1 = document.querySelector('#txtPor1').value;
            let txtPrecio1 = document.querySelector('#txtPrecio1').value;
            let txtPor2 = document.querySelector('#txtPor2').value;
            let txtPrecio2 = document.querySelector('#txtPrecio2').value;
            let txtPor3 = document.querySelector('#txtPor3').value;
            let txtPrecio3 = document.querySelector('#txtPrecio3').value;
            let txtPor4 = document.querySelector('#txtPor4').value;
            let txtPrecio4 = document.querySelector('#txtPrecio4').value;
            let txtMax = document.querySelector('#txtMax').value;
            let txtMin = document.querySelector('#txtMin').value;
            //Descripcion
            let txtDescripcion = document.querySelector('#txtDescripcion').value;

            if(txtNombre == '' || txtCodigo == '' || txtLista == '' || txtMax == '' || txtMin == '' ){
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            if(txtCodigo.length < 5){
                swal("Atención", "El código debe ser mayor que 5 dígitos." , "error");
                return false;
            }
            //divLoading.style.display = "flex";
            tinyMCE.triggerSave();
            let request = (window.XMLHttpRequest) ? 
                            new XMLHttpRequest() : 
                            new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Items/ingresarItem'; 
            let formData = new FormData(formProductos);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("", objData.msg ,"success");
                        document.querySelector("#idProducto").value = objData.idproducto;
                        document.querySelector("#containerGallery").classList.remove("notblock");

                        if(rowTable == ""){//Nuevo
                            tableProductos.api().ajax.reload();
                        }else{
                            //Modificacion para no refrestar los datos
                           let intStock=document.querySelector('#txth_stock').value;
                           htmlStatus = cmb_estado == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[0].textContent = txtCodigo;
                            rowTable.cells[1].textContent = txtNombre;
                            rowTable.cells[2].textContent = lineaName;
                            rowTable.cells[3].textContent = intStock;
                            rowTable.cells[4].textContent = txtPrecio4;
                            //rowTable.cells[4].textContent = smony+txtPrecio4;
                            rowTable.cells[5].innerHTML =  htmlStatus;
                            rowTable = ""; 
                        }
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                //divLoading.style.display = "none";
                return false;
            }
        }
    }

    //AGREGAR LA IMAGEN
    if(document.querySelector(".btnAddImage")){
       let btnAddImage =  document.querySelector(".btnAddImage");
       btnAddImage.onclick = function(e){
        let key = Date.now();//id Diferente
        let newElement = document.createElement("div");
        newElement.id= "div"+key;
        newElement.innerHTML = `
            <div class="prevImage"></div>
            <input type="file" name="foto" id="img${key}" class="inputUploadfile">
            <label for="img${key}" class="btnUploadfile"><i class="fa fa-upload "></i></label>
            <button class="btnDeleteImage notblock" type="button" onclick="fntDelItem('#div${key}')"><i class="fa fa-trash"></i></button>`;
        document.querySelector("#containerImages").appendChild(newElement);
        document.querySelector("#div"+key+" .btnUploadfile").click();
        fntInputFile();
       }
    }

  

    fntInputFile();
    //inicioText();
    fntLineas();

   
}, false);

function eventoEnter(valor,control){
    if (valor) {//Si el usuario Presiono Enter= True
        //calculoCostos();
        control.value = redondea(control.value, N4decimal);
        
    }
}

//Se ejecuta en los eventos de Controles
$(document).ready(function() {
    $("#txtPorCostos").blur(function() {
        let Costo = document.querySelector('#txtPromedio').value;
        let margen = document.querySelector('#txtPorCostos').value;
        document.querySelector('#txtPorCostos').value=redondea(margen, N2decimal);
        document.querySelector('#txtCostos').value=calculoCostos(Costo,margen,N4decimal);
    });

    $("#txtPor1").blur(function() {
        let Costo = document.querySelector('#txtCostos').value;
        let margen = document.querySelector('#txtPor1').value;
        document.querySelector('#txtPor1').value=redondea(margen, N2decimal);
        document.querySelector('#txtPrecio1').value=calculoCostos(Costo,margen,N4decimal);
    });

    $("#txtPor2").blur(function() {
        let Costo = document.querySelector('#txtCostos').value;
        let margen = document.querySelector('#txtPor2').value;
        document.querySelector('#txtPor2').value=redondea(margen, N2decimal);
        document.querySelector('#txtPrecio2').value=calculoCostos(Costo,margen,N4decimal);
    });

    $("#txtPor3").blur(function() {
        let Costo = document.querySelector('#txtCostos').value;
        let margen = document.querySelector('#txtPor3').value;
        document.querySelector('#txtPor3').value=redondea(margen, N2decimal);
        document.querySelector('#txtPrecio3').value=calculoCostos(Costo,margen,N4decimal);
    });

    $("#txtPor4").blur(function() {
        let Costo = document.querySelector('#txtCostos').value;
        let margen = document.querySelector('#txtPor4').value;
        document.querySelector('#txtPor4').value=redondea(margen, N2decimal);
        document.querySelector('#txtPrecio4').value=calculoCostos(Costo,margen,N4decimal);
    });

    /*$('#cmb_establecimiento').change(function () {        
        if ($('#cmb_establecimiento').val() != 0) {            
            fntPunto();
        } else {
            $('#cmb_punto option').remove();
            $('#cmb_punto').selectpicker('refresh')
            swal("Error", "Selecione Establecimiento" , "error");
        }
    });

    $("#ids-sx").keyup(function() {
        //codigo
    });
    $("#btnActionForm").click(function() {
        guardarDatos();
    });*/
});

/************************************* */
//Codigo de Barras
if(document.querySelector("#txtCodigo")){
    let inputCodigo = document.querySelector("#txtCodigo");
    inputCodigo.onkeyup = function() {
        if(inputCodigo.value.length >= 5){
            document.querySelector('#divBarCode').classList.remove("notblock");
            fntBarcode();
       }else{
            document.querySelector('#divBarCode').classList.add("notblock");
       }
    };
}

function fntBarcode(){
    let codigo = document.querySelector("#txtCodigo").value;
    JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area){
    let elemntArea = document.querySelector(area);
    let vprint = window.open(' ', 'popimpr', 'height=400,width=600');
    vprint.document.write(elemntArea.innerHTML );
    vprint.document.close();
    vprint.print();
    vprint.close();
}
/****************************** */

//Plugin de TEXTO
tinymce.init({
	selector: '#txtDescripcion',
	width: "100%",
    height: 400,    
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});

//SUBIR LA IMAGEN AL SERVIDOR
function fntInputFile(){
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    inputUploadfile.forEach(function(inputUploadfile) {
        inputUploadfile.addEventListener('change', function(){
            let idProducto = document.querySelector("#idProducto").value;
            //let idProducto = "GEN001";
            let parentId = this.parentNode.getAttribute("id");
            let idFile = this.getAttribute("id");            
            let uploadFoto = document.querySelector("#"+idFile).value;
            let fileimg = document.querySelector("#"+idFile).files;
            let prevImg = document.querySelector("#"+parentId+" .prevImage");
            let nav = window.URL || window.webkitURL;
            if(uploadFoto !=''){//Valida si existe una Imagen
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                //Valida los Archivos Permitidos
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                    //Si la extension no es correcta
                    prevImg.innerHTML = "Archivo no válido";
                    uploadFoto.value = "";
                    return false;
                }else{
                    //Procede a Subir el Archivo
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;//LODADING

                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = base_url+'/Items/setImage'; 
                    let formData = new FormData();
                    formData.append('idproducto',idProducto);
                    formData.append("foto", this.files[0]);//Datos de la Foto
                    request.open("POST",ajaxUrl,true);
                    request.send(formData);
                    request.onreadystatechange = function(){
                        if(request.readyState != 4) return;
                        if(request.status == 200){
                            let objData = JSON.parse(request.responseText);
                            if(objData.status){
                                prevImg.innerHTML = `<img src="${objeto_url}">`;
                                document.querySelector("#"+parentId+" .btnDeleteImage").setAttribute("imgname",objData.imgname);
                                document.querySelector("#"+parentId+" .btnUploadfile").classList.add("notblock");
                                document.querySelector("#"+parentId+" .btnDeleteImage").classList.remove("notblock");
                            }else{
                                swal("Error", objData.msg , "error");
                            }
                        }
                    }

                }
            }

        });
    });
}

//Eliminar Imagen
function fntDelImg(element){
    let nameImg = document.querySelector(element+' .btnDeleteImage').getAttribute("imgname");
    let idProducto = document.querySelector("#idProducto").value;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Items/eliminarArchivo'; 

    let formData = new FormData();
    formData.append('idproducto',idProducto);
    formData.append("file",nameImg);
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function(){
        if(request.readyState != 4) return;
        if(request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                let itemRemove = document.querySelector(element);
                itemRemove.parentNode.removeChild(itemRemove);
            }else{
                swal("", objData.msg , "error");
            }
        }
    }
}

function fntView(idProducto){
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Items/getProducto/'+idProducto;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status){
                //item_id,item_nombre,item_descripcion,lin_id,tip_id,mar_id,umed_id,item_nombre_percha,item_ubiacion_percha,item_graba_iva,
				//item_precio_lista,item_precio_promedio,item_precio_costo,item_por_precio1,item_precio1,item_por_precio2,item_precio2,
				//item_por_precio3,item_precio3,item_por_venta,item_precio_venta,item_existencia_maxima,item_existencia_minima
                let htmlImage = "";
                let objProducto = objData.data;
                let estadoProducto = objProducto.estado_logico == 1 ? 
                    '<span class="badge badge-success">Activo</span>' : 
                    '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celCodigo").innerHTML = objProducto.item_id;
                document.querySelector("#celNombre").innerHTML = objProducto.item_nombre;
                document.querySelector("#celPrecio").innerHTML = objProducto.item_precio_venta;
                document.querySelector("#celStock").innerHTML = objProducto.Stock;
                //document.querySelector("#celCategoria").innerHTML = objProducto.categoria;
                document.querySelector("#celStatus").innerHTML = estadoProducto;
                document.querySelector("#celDescripcion").innerHTML = objProducto.item_descripcion;

                if(objProducto.images.length > 0){
                    let objProductos = objProducto.images;
                    for (let p = 0; p < objProductos.length; p++) {
                        htmlImage +=`<img src="${objProductos[p].url_image}"></img>`;
                    }
                }
                document.querySelector("#celFotos").innerHTML = htmlImage;
                $('#modalViewProducto').modal('show');

            }else{
                swal("Error", objData.msg , "error");
            }
        }
    } 
}

function fntEdit(element,idProducto){
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML ="Actualizar Producto";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";
    let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Items/getProducto/'+idProducto;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status){
                let htmlImage = "";
                let objProducto = objData.data;

                //item_id,item_nombre,item_descripcion,lin_id,tip_id,mar_id,umed_id,item_nombre_percha,item_ubiacion_percha,item_graba_iva,
				//item_precio_lista,item_precio_promedio,item_precio_costo,item_por_precio1,item_precio1,item_por_precio2,item_precio2,
				//item_por_precio3,item_precio3,item_por_venta,item_precio_venta,item_existencia_maxima,item_existencia_minima

                //Informacion
                document.querySelector("#idProducto").value = objProducto.item_id;
                document.querySelector('#txtNombre').value= objProducto.item_nombre;
                document.querySelector('#txtCodigo').value= objProducto.item_id;
                document.querySelector('#cmb_linea').value= objProducto.lin_id;
                document.querySelector('#cmb_tipo').value= objProducto.tip_id;
                document.querySelector('#cmb_marca').value= objProducto.mar_id;
                document.querySelector('#cmb_medida').value= objProducto.umed_id;
                document.querySelector('#txtPercha').value= objProducto.item_nombre_percha;
                document.querySelector('#txtUbicacion').value= objProducto.item_ubiacion_percha;
                document.querySelector('#cmb_iva').value= objProducto.item_graba_iva;
                document.querySelector('#cmb_estado').value= objProducto.estado_logico;
                //document.querySelector('#cmb_bodega').value= objProducto.item_id;
                //Precios
                document.querySelector('#txtLista').value= objProducto.item_precio_lista;
                document.querySelector('#txtPromedio').value= objProducto.item_precio_promedio;
                document.querySelector('#txtPorCostos').value= objProducto.item_por_costo;
                document.querySelector('#txtCostos').value= objProducto.item_precio_costo;
                document.querySelector('#txtPor1').value= objProducto.item_por_precio1;
                document.querySelector('#txtPrecio1').value= objProducto.item_precio1;
                document.querySelector('#txtPor2').value= objProducto.item_por_precio2;
                document.querySelector('#txtPrecio2').value= objProducto.item_precio2;
                document.querySelector('#txtPor3').value= objProducto.item_por_precio3;
                document.querySelector('#txtPrecio3').value= objProducto.item_precio3;
                document.querySelector('#txtPor4').value= objProducto.item_por_venta;
                document.querySelector('#txtPrecio4').value= objProducto.item_precio_venta;
                document.querySelector('#txtMax').value= objProducto.item_existencia_maxima;
                document.querySelector('#txtMin').value= objProducto.item_existencia_minima;

                document.querySelector('#txth_stock').value= objProducto.Stock;
                
                //Descripcion
                if(objProducto.item_descripcion!=""){
                    document.querySelector('#txtDescripcion').value= objProducto.item_descripcion;
                    tinymce.activeEditor.setContent(objProducto.item_descripcion); 
                }else{
                    document.querySelector('#txtDescripcion').value= "";
                    tinymce.activeEditor.setContent(""); 
                }
                
                $('#cmb_linea').selectpicker('render');
                $('#cmb_tipo').selectpicker('render');
                $('#cmb_marca').selectpicker('render');
                $('#cmb_medida').selectpicker('render');
                $('#cmb_iva').selectpicker('render');
                $('#cmb_estado').selectpicker('render');
                fntBarcode();

                $("#txtCodigo").prop('disabled', true);
                $("#txtLista").prop('disabled', true);
                $("#txtPromedio").prop('disabled', true);

                if(objProducto.images.length > 0){
                    let objProductos = objProducto.images;
                    for (let p = 0; p < objProductos.length; p++) {
                        let key = Date.now()+p;//Id para los DIV
                        htmlImage +=`<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objProductos[p].url_image}"></img>
                            </div>
                            <button type="button" class="btnDeleteImage" onclick="fntDelImg('#div${key}')" imgname="${objProductos[p].img_nombre}">
                            <i class="fa fa-trash"></i></button></div>`;
                    }
                }
                document.querySelector("#containerImages").innerHTML = htmlImage; 
                document.querySelector("#divBarCode").classList.remove("notblock");
                document.querySelector("#containerGallery").classList.remove("notblock");           
                $('#modalFormProductos').modal('show');

            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelete(idProducto){
    swal({
        title: "Eliminar Producto",
        text: "¿Realmente quiere eliminar el producto?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Items/delItem';
            let strData = "idProducto="+idProducto;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("Eliminar!", objData.msg , "success");
                        tableProductos.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });

}



function fntLineas(){
    //alert("linea");
    if(document.querySelector('#cmb_linea')){
        let ajaxUrl = base_url+'/Items/getLinea';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#cmb_linea').innerHTML = request.responseText;
                $('#cmb_linea').selectpicker('render');
                fntTipos();
                
            }
        }
    }
}

function fntTipos(){
    if(document.querySelector('#cmb_tipo')){
        let ajaxUrl = base_url+'/Items/getTipo';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#cmb_tipo').innerHTML = request.responseText;
                $('#cmb_tipo').selectpicker('render');
                fntMarcas();
                
            }
        }
    }
}

function fntMarcas(){
    if(document.querySelector('#cmb_marca')){
        let ajaxUrl = base_url+'/Items/getMarca';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#cmb_marca').innerHTML = request.responseText;
                $('#cmb_marca').selectpicker('render');
                fntUnidadMedida();
            }
        }
    }
}

function fntUnidadMedida(){
    if(document.querySelector('#cmb_medida')){
        let ajaxUrl = base_url+'/Items/getUnidadMedida';
        let request = (window.XMLHttpRequest) ? 
                    new XMLHttpRequest() : 
                    new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#cmb_medida').innerHTML = request.responseText;
                $('#cmb_medida').selectpicker('render');
                //fntBodegas();
            }
        }
    }
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

function openModal(){   
    rowTable = "";
    //inicioText();

    $("#txtCodigo").prop('disabled', false);
    $("#txtLista").prop('disabled', false);
    $("#txtPromedio").prop('disabled', false);

    document.querySelector('#idProducto').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Item";
    document.querySelector("#formProductos").reset();
    document.querySelector("#divBarCode").classList.add("notblock");
    document.querySelector("#containerGallery").classList.add("notblock");
    document.querySelector("#containerImages").innerHTML = "";
    $('#modalFormProductos').modal('show');
}

function inicioText(){
    //alert('llego');
    $('#txtMin').val(0);
    $('#txtMax').val(0);
    document.querySelector("#txtLista").value =parseFloat(0).toFixed(N4decimal);
    document.querySelector("#txtPromedio").value = parseFloat(0).toFixed(N4decimal);
    //$('#txtLista').val(parseFloat(0).toFixed(N4decimal));
    $('#txtPromedio').val(parseFloat(0).toFixed(N4decimal));
    $('#txtCostos').val(parseFloat(0).toFixed(N4decimal));
    $('#txtPor1').val(parseFloat(0).toFixed(N2decimal));
    $('#txtPrecio1').val(parseFloat(0).toFixed(N4decimal));
    $('#txtPor2').val(parseFloat(0).toFixed(N2decimal));
    $('#txtPrecio2').val(parseFloat(0).toFixed(N4decimal));
    $('#txtPor3').val(parseFloat(0).toFixed(N2decimal));
    $('#txtPrecio3').val(parseFloat(0).toFixed(N4decimal));
    $('#txtPor4').val(parseFloat(0).toFixed(N2decimal));
    $('#txtPrecio4').val(parseFloat(0).toFixed(N4decimal));
    
} 