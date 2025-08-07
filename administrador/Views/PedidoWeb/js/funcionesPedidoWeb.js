let tableTienda;
const tGrid = "TbG_ListaItems";
const storageKey = "dts_PrecioListaItems";
let alertaTimeout;

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
            { "data": "numero" },
            { "data": "fechapedido" },
            { "data": "cped_id" },
            { "data": "nombretienda" },
            { "data": "nombrepersona" },
            { "data": "total" },
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

    // Verifica si el campo txth_ids tiene un valor antes de continuar
    const txthIds = document.querySelector('#txth_ids');
    if (txthIds && txthIds.value !== "") {
        actualizarTabla();

    }


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

    $("#btnGuardar").click(function () {
        guardarPedido();
    });

});
//}, false);


function obtenerInfoTienda() {
    let idsTienda = $('#cmb_tienda').val();
    let url = base_url + '/pedidoWeb/retornarDatosTienda';
    var metodo = 'POST';
    var datos = { ids: idsTienda };
    peticionAjaxSSL(url, metodo, datos, function (data) {
        data.data.SaldoTienda
        if (data.status) {
            let saldoCupo = parseFloat(data.data.Cupo) - parseFloat(data.data.SaldoTienda);
            $('#lbl_cupo').text(data.data.Cupo);
            $('#lbl_contacto').text(data.data.ContactoTienda);
            $('#lbl_direccion').text(data.data.Direccion);
            $('#lbl_telefono').text(data.data.Telefono);
            $('#lbl_cupoSaldo').text(saldoCupo.toFixed(N2decimal));
            $('#lbl_cupoUsado').text((data.data.SaldoTienda).toFixed(N2decimal));
            guardarProductosEnStorage(data.data.Items);
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

function guardarProductosEnStorage(productos) {
    sessionStorage.setItem(storageKey, JSON.stringify(productos));
}

function verificarImagen(url, callback) {
    let img = new Image();
    img.src = url;
    img.onload = () => callback(true);  // La imagen existe
    img.onerror = () => callback(false); // La imagen no existe
}

function filtrarTabla() {
    let searchValue = document.getElementById("txtCodigoProducto").value.toLowerCase();
    let filas = document.querySelectorAll("#TbG_ListaItems tbody tr");

    filas.forEach(fila => {
        let textoFila = fila.innerText.toLowerCase();
        fila.style.display = textoFila.includes(searchValue) ? "" : "none";
    });
}



function actualizarTabla() {
    const tbody = document.querySelector(`#${tGrid} tbody`);
    tbody.innerHTML = "";
    const productos = obtenerProductosGuardados();
    let imgDefault = "/imagenes/no_image.jpg"; // Imagen por defecto si no existe

    productos.forEach((producto, index) => {
        let imgPath = `/imagenes/${producto.codigo}_G-01.jpg`;

        verificarImagen(imgPath, (existe) => {
            let finalImgPath = existe ? imgPath : imgDefault;

            try {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${producto.codigo}</td>
                    <td>${producto.nombre}</td>
                    <td>
                        <input type="number" 
                            value="${parseFloat(producto.cantidad).toFixed(N2decimal)}" 
                            min="0" step="0.01"
                            data-index="${index}" 
                            class="form-control text-end cantidad-input" 
                            style="width: auto; min-width: 30px; text-align: right;" />
                    </td>
                    <td class="precio">${parseFloat(producto.precio).toFixed(N2decimal)}</td>
                    <td class="total">${(producto.cantidad * producto.precio).toFixed(N2decimal)}</td>
                    <td>
                        <img src="${finalImgPath}" alt="Producto" width="50" class="img-thumbnail"
                            onclick="abrirGaleria(['${producto.codigo}_G-01.jpg', '${producto.codigo}_G-02.jpg', '${producto.codigo}_G-03.jpg'])">
                    </td>
                `;
                tbody.appendChild(row);
            } catch (error) {
                console.log("Error al agregar la fila a la tabla:", error);
            }

            if (index === productos.length - 1) {
                asignarEventosCantidad();
                actualizarTotalGeneral();
            }
        });
    });
}

function asignarEventosCantidad() {
    const inputs = document.querySelectorAll(".cantidad-input");

    inputs.forEach(input => {
        input.addEventListener("blur", () => procesarCambioCantidad(input));
        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                e.preventDefault();
                input.blur(); // Simula blur para usar la misma lógica
            }
        });
    });
}

function procesarCambioCantidad(input) {
    const index = parseInt(input.dataset.index);
    const productos = obtenerProductosGuardados();

    let nuevaCantidad = parseFloat(input.value);
    if (isNaN(nuevaCantidad) || nuevaCantidad < 0) nuevaCantidad = 0;

    // Formatear y asignar al input
    nuevaCantidad = parseFloat(nuevaCantidad).toFixed(N2decimal);
    input.value = nuevaCantidad;

    // Actualizar en el objeto de sessionStorage
    productos[index].cantidad = parseFloat(nuevaCantidad);
    productos[index].total = parseFloat(nuevaCantidad) * parseFloat(productos[index].precio);

    // Actualizar fila
    const row = input.closest("tr");
    row.querySelector(".total").textContent = productos[index].total.toFixed(N2decimal);

    // Guardar en sessionStorage
    guardarProductosEnStorage(productos);

    // Recalcular total general
    actualizarTotalGeneral();
}


function actualizarTotalGeneral() {
    let totalGeneral = 0;
    document.querySelectorAll("td.total").forEach(td => {
        totalGeneral += parseFloat(td.textContent) || 0;
    });

    // Actualizar texto de total
    const lblTotalGeneral = document.getElementById("lblTotalGeneral");
    if (lblTotalGeneral) {
        lblTotalGeneral.textContent = `Total General: ${totalGeneral.toFixed(N2decimal)}`;
    }

    // Comparar contra el cupo asignado
    //const lblCupo = document.getElementById("lbl_cupo");//Otorgado
    const lblCupo = document.getElementById("lbl_cupoSaldo");//Saldo a la fecha
    const cupoOtorgado = parseFloat(lblCupo?.textContent || 0);

    if (totalGeneral > cupoOtorgado) {
        const excedido = (totalGeneral - cupoOtorgado).toFixed(N2decimal);
        mostrarAlertaCupo(`Has sobrepasado el cupo asignado en <strong>$${excedido}</strong>.`, "danger");

    } else if (totalGeneral === cupoOtorgado) {
        mostrarAlertaCupo(`Has alcanzado exactamente tu cupo asignado.`, "warning");

    } else {
        const restante = (cupoOtorgado - totalGeneral).toFixed(N2decimal);
        mostrarAlertaCupo(`Tienes un cupo disponible de <strong>$${restante}</strong>.`, "info");
    }
}

function mostrarAlertaCupo(mensaje = "", tipo = "danger") {
    const alerta = document.getElementById("alerta-cupo");
    const mensajeElemento = document.getElementById("alerta-cupo-mensaje");
    if (!alerta || !mensajeElemento) return;

    // Quitar clases anteriores (si las hubiera)
    alerta.classList.remove("alert-danger", "alert-success", "alert-warning", "alert-info");

    // Agregar clase correspondiente al tipo
    alerta.classList.add(`alert-${tipo}`);

    // Actualizar contenido
    mensajeElemento.innerHTML = `<strong>¡Atención!</strong> ${mensaje}`;

    // Mostrar
    alerta.classList.remove("d-none");

    // Reiniciar temporizador
    clearTimeout(alertaTimeout);
    alertaTimeout = setTimeout(() => {
        ocultarAlertaCupo();
    }, 60000);
}

function ocultarAlertaCupo() {
    const alerta = document.getElementById("alerta-cupo");
    if (!alerta) return;

    alerta.classList.add("d-none");
}


function abrirGaleria(imagenes) {
    let carouselInner = document.getElementById("carouselInner");
    carouselInner.innerHTML = "";
    let imgDefault = "/imagenes/no_image.jpg"; // Imagen por defecto si no existe

    imagenes.slice(0, 3).forEach((img, index) => {
        let activeClass = index === 0 ? "active" : "";
        let imgPath = `/imagenes/${img}`;

        verificarImagen(imgPath, (existe) => {
            let finalImgPath = existe ? imgPath : imgDefault;

            let item = `
                <div class="carousel-item ${activeClass}">
                    <img src="${finalImgPath}" class="d-block w-100 img-fluid" alt="Imagen">
                </div>
            `;
            carouselInner.innerHTML += item;

            // Si es la última imagen, inicializar el carrusel
            if (index === imagenes.length - 1) {
                let carousel = new bootstrap.Carousel(document.getElementById("carouselGaleria"));
                carousel.to(0);
            }
        });
    });

    new bootstrap.Modal(document.getElementById("modalGaleria")).show();
}




function guardarPedido() {
    let accion = ($('#btnText').html() == "Guardar") ? 'Create' : 'Edit';
    let CabIds = document.querySelector('#txth_ids').value;
    const cmbTienda = document.getElementById("cmb_tienda");
    const tiendaSeleccionada = cmbTienda ? cmbTienda.value : "0";

    if (accion === "Create") {
        if (!tiendaSeleccionada || tiendaSeleccionada === "0") {
            mostrarAlertaCupo("Debe seleccionar una tienda antes de guardar.", "warning");
            return;
        }
    }


    const productos = obtenerProductosGuardados(); // Función que retorna el array original
    const filas = document.querySelectorAll(`#${tGrid} tbody tr`);
    const productosModificados = [];

    let totalGeneral = 0;

    filas.forEach((fila, index) => {
        const inputCantidad = fila.querySelector(".cantidad-input");
        const cantidad = parseFloat(inputCantidad.value) || 0;
        const producto = productos[index];

        if (cantidad > 0) {
            const total = (cantidad * parseFloat(producto.precio)).toFixed(N2decimal);
            totalGeneral += parseFloat(total);

            productosModificados.push({
                ...producto,
                cantidad: cantidad,
                total: parseFloat(total).toFixed(N2decimal)
            });
        }
    });

    const cupoOtorgado = parseFloat(document.getElementById("lbl_cupo").textContent) || 0;

    if (totalGeneral > cupoOtorgado) {
        const excedido = (totalGeneral - cupoOtorgado).toFixed(N2decimal);
        mostrarAlertaCupo(`No se puede guardar. El total supera el cupo en $${excedido}.`, "danger");
        return;
    }

    if (productosModificados.length === 0) {
        mostrarAlertaCupo("Debe ingresar al menos una cantidad válida mayor a cero para guardar.", "warning");
        return;
    }
    let url = base_url + '/pedidoWeb/ingresarPedidoTemp';
    var metodo = 'POST';
    var dataPost = {
        accion: accion,
        cabIds: CabIds,
        tienda_id: tiendaSeleccionada,
        productos: productosModificados,
        total: totalGeneral
    };
    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        // Manejar el éxito de la solicitud aquí
        if (data.status) {
            swal("Pedido N°: " + data.numero, data.msg, "success");
        } else {
            swal("Atención", data.msg, "error");
        }

    }, function (jqXHR, textStatus, errorThrown) {
        // Manejar el error de la solicitud aquí
        console.log('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
    });

}



function fntAnularPedido(ids) {
    swal({
        title: "Anular Registro",
        text: "¿Realmente quiere anular el Registro?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Anular!",
        cancelButtonText: "No, Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            let url = base_url + '/pedidoWeb/anularPedidoTemp';
            var metodo = 'POST';
            var dataPost = {
                ids: ids
            };
            peticionAjaxSSL(url, metodo, dataPost, function (data) {
                // Manejar el éxito de la solicitud aquí
                if (data.status) {
                    swal("Anular!", data.msg, "success");
                        tableTienda.api().ajax.reload(function () {

                                                });
                } else {
                    swal("Atención", data.msg, "error");
                }

            }, function (jqXHR, textStatus, errorThrown) {
                // Manejar el error de la solicitud aquí
                console.log('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
            });
        }

    });

}

function fntAutorizarPedido(ids) {
    swal({
        title: "Autorizar Registro",
        text: "¿Realmente quiere Autorizar el Registro?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Autorizar!",
        cancelButtonText: "No, Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {
        if (isConfirm) {
            let url = base_url + '/pedidoWeb/autorizarPedidoTemp';
            var metodo = 'POST';
            var dataPost = {
                ids: ids
            };
            peticionAjaxSSL(url, metodo, dataPost, function (data) {
                // Manejar el éxito de la solicitud aquí
                if (data.status) {
                    swal("Autorizado!", data.msg, "success");
                        tableTienda.api().ajax.reload(function () {           });
                } else {
                    swal("Atención", data.msg, "error");
                }

            }, function (jqXHR, textStatus, errorThrown) {
                // Manejar el error de la solicitud aquí
                console.log('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
            });
        }

    });

}





