let tableTienda;
const tGrid = "TbG_ListaItems";
const storageKey = "dts_PrecioListaItems";

document.addEventListener('DOMContentLoaded', function () {
    tableTienda = $('#tableTienda').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/PedidoWeb/consultarPedidos",
            "dataSrc": ""
        },
        "columns": [
            { "data": "pedid" },
            { "data": "fechapedido" },
            { "data": "numero" },
            { "data": "NombreTienda" },
            { "data": "NombrePersona" },
            { "data": "Total" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0] },
            { 'className': "textleft", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textleft", "targets": [3] },
            { 'className': "textleft", "targets": [4] },
            { 'className': "textcenter", "targets": [5] },
            { 'className': "textcenter", "targets": [6] },
            { 'className': "textcenter", "targets": [7] }
        ],
        'dom': 'lBfrtip',
        'buttons': [],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": numPaginado,//Numero Items Retornados
        "order": [[1, orderBy]]  //Orden por defecto 1 columna
    });


    const precioInput = document.getElementById("txt_PrecioProducto");
    const btnAgregar = document.getElementById("btnAgregar");


});

$(document).ready(function () {
    $('#cmb_tienda').selectpicker();
    //Nueva Orden
    $("#btn_nuevopedido").click(function () {
        //eliminarStores();
        window.location = base_url + '/pedidoWeb/nuevo';//Retorna al Portal Principal
    });

    $("#btn_retornar").click(function () {
        //const tbody = document.querySelector(`#TbG_Tiendas tbody`);
        //tbody.innerHTML = "";        
        //eliminarClavesSessionStorage("dts_precioTienda");
        limpiarSessionStorage();
        window.location = base_url + '/pedidoWeb';//Retorna al Portal Principal
    });

    $('#cmb_tienda').change(function () {
        if ($('#cmb_tiendas').val() != 0) {
            obtenerInfoTienda();
        } else {
            //$('#txt_numero_horas').val("0");
            swal("Error", "Selecione una Tienda", "error");
        }
    });



});


function obtenerInfoTienda() {
    let idsTienda = $('#cmb_tienda').val();
    let url = base_url + '/pedidoWeb/retornarDatosTienda';
    var metodo = 'POST';
    var datos = { ids: idsTienda };
    peticionAjaxSSL(url, metodo, datos, function (data) {
        if (data.status) {
            $('#lbl_cupo').text(data.data.Cupo);
            $('#lbl_contacto').text(data.data.ContactoTienda);
            $('#lbl_direccion').text(data.data.Direccion);
            $('#lbl_telefono').text(data.data.Telefono);
            actualizarTabla();

            //eliminarClavesSessionStorage('seleccionados');

        } else {
            swal("Atención", data.msg, "error");
        }

    }, function (jqXHR, textStatus, errorThrown) {
        console.log('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });

}

function obtenerProductosGuardados() {
    return JSON.parse(sessionStorage.getItem(storageKey)) || [];
}

function actualizarTabla() {
    const tbody = document.querySelector(`#${tGrid} tbody`);
    tbody.innerHTML = "";
    const productos = obtenerProductosGuardados();
    let imgDefault = "/imagenes/no_image.jpg"; // Imagen por defecto si no existe

    productos.forEach((producto) => {
        let imgPath = `/imagenes/${producto.cod_art}_G-01.jpg`;

        verificarImagen(imgPath, (existe) => {
            let finalImgPath = existe ? imgPath : imgDefault;

            try {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>
                        <input type="checkbox" class="row-check" data-id="${producto.pcli_id}">
                    </td>
                    <td>${producto.cod_art}</td>
                    <td>${producto.des_com}</td>
                    <td>
                        <img src="${finalImgPath}" alt="Producto" width="50" class="img-thumbnail"
                            onclick="abrirGaleria(['${producto.cod_art}_G-01.jpg', '${producto.cod_art}_G-02.jpg', '${producto.cod_art}_G-03.jpg'])">
                    </td>
                `;
                tbody.appendChild(row);

                // Recuperar el checkbox recién agregado
                const checkbox = row.querySelector(".row-check");

      
                // Restaurar la selección según sessionStorage
                let seleccionados = JSON.parse(sessionStorage.getItem("seleccionados")) || [];
                if (seleccionados.includes(String(producto.pcli_id))) {
                    checkbox.checked = true;
                }

                // Adjuntar el evento change al checkbox
                checkbox.addEventListener("change", function () {
                    almacenarSeleccion(this.getAttribute("data-id"), this.checked);
                    actualizarSeleccionGlobal();
                    //console.log("Seleccionados:", sessionStorage.getItem("seleccionados"));
                });
            } catch (error) {
                console.error("Error al agregar la fila a la tabla:", error);
            }
        });
    });
}



