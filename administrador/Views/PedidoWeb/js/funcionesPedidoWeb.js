let tableTienda;
const tGrid = "TbG_ListaItems";
const storageKey = "dts_PrecioListaItems";
let alertaTimeout;
let modoAccion; // Variable para almacenar el modo (Nuevo o Editar)

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
            { 'className': "textcenter", "targets": [5], "render": function(data, type, row) {
                    var val = parseFloat(data);
                    return isNaN(val) ? data : val.toFixed(2);
                }
            },
            { 'className': "textcenter", "targets": [6] },
            { 'className': "textcenter", "targets": [7] }
        ],
        'dom': 'lBfrtip',
        'buttons': [],
        "responsive": true,
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
    
    // Inicializar estado del botón guardar
    deshabilitarBotonGuardar();
    
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
        const tiendaSeleccionada = $(this).val();
        if (tiendaSeleccionada && tiendaSeleccionada !== '0' && tiendaSeleccionada !== '') {
            obtenerInfoTienda();
        } else {
            limpiarDatosTienda();
            deshabilitarBotonGuardar();
            mostrarAlertaCupo("Debe seleccionar una tienda para continuar.", "warning");
        }
    });

    $("#btnGuardar").click(function () {
        guardarPedido();
    });

});
//}, false);


function obtenerInfoTienda() {
    let idsTienda = $('#cmb_tienda').val();
    
    // Validar que se haya seleccionado una tienda
    if (!idsTienda || idsTienda === '0' || idsTienda === '') {
        swal("Error", "Debe seleccionar una tienda", "error");
        // Limpiar datos y deshabilitar botón guardar
        limpiarDatosTienda();
        deshabilitarBotonGuardar();
        return;
    }
    
    let url = base_url + '/pedidoWeb/retornarDatosTienda';
    var metodo = 'POST';
    var datos = { ids: idsTienda };
    
    peticionAjaxSSL(url, metodo, datos, function (data) {
        if (data.status) {
            let saldoCupo = parseFloat(data.data.Cupo) - parseFloat(data.data.SaldoTienda);
            
            // Actualizar elementos de la interfaz
            $('#lbl_cupo').text(data.data.Cupo);
            $('#lbl_contacto').text(data.data.ContactoTienda);
            $('#lbl_direccion').text(data.data.Direccion);
            $('#lbl_telefono').text(data.data.Telefono);
            $('#lbl_cupoSaldo').text(saldoCupo.toFixed(N2decimal));
            $('#lbl_cupoUsado').text((data.data.SaldoTienda).toFixed(N2decimal));
            
            // Verificar si hay saldo disponible
            if (saldoCupo <= 0) {
                mostrarAlertaCupo("No tiene cupo disponible. No podrá realizar pedidos.", "danger");
                deshabilitarBotonGuardar();
            } else {
                habilitarBotonGuardar();
                mostrarAlertaCupo(`Cupo disponible: $${saldoCupo.toFixed(N2decimal)}`, "info");
            }
            
            // Guardar productos y actualizar tabla solo si hay items
            if (data.data.Items && data.data.Items.length > 0) {
                guardarProductosEnStorage(data.data.Items);
                actualizarTabla();
            } else {
                mostrarAlertaCupo("No hay productos disponibles para esta tienda.", "warning");
                limpiarTablaProductos();
            }

        } else {
            swal("Atención", data.msg, "error");
            limpiarDatosTienda();
            deshabilitarBotonGuardar();
        }

    }, function (jqXHR, textStatus, errorThrown) {
        console.log('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        swal("Error", "Error al conectar con el servidor", "error");
        limpiarDatosTienda();
        deshabilitarBotonGuardar();
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


async function actualizarTabla() {
    const tbody = document.querySelector(`#${tGrid} tbody`);
    tbody.innerHTML = "";

    const productos = obtenerProductosGuardados();
    const imgDefault = "/imagenes/no_image.jpg"; // Imagen por defecto

    for (const [index, producto] of productos.entries()) {
        const imgPath = `/imagenes/${producto.codigo}_G-01.jpg`;
        const existe = await verificarImagenAsync(imgPath);
        const finalImgPath = existe ? imgPath : imgDefault;

        try {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${producto.codigo}</td>
                <td>${producto.nombre}</td>
                <td>
                    <input type="text" 
                            value="${parseInt(producto.cantidad)}" 
                            inputmode="numeric" pattern="[0-9]*"
                            data-index="${index}" 
                            class="form-control text-end cantidad-input" 
                            style="width: auto; min-width: 30px; text-align: right;" />
                </td>
                <td class="precio text-end">${parseFloat(producto.precio).toFixed(N2decimal)}</td>
                <td class="total text-end">${(producto.cantidad * producto.precio).toFixed(N2decimal)}</td>
                <td class="text-center">
                    <img src="${finalImgPath}" alt="Producto" width="50" height="50" 
                        class="img-thumbnail" 
                        style="cursor: pointer;"
                        onclick="abrirGaleria(['${producto.codigo}_G-01.jpg'])">
                </td>
            `;
            tbody.appendChild(row);
        } catch (error) {
            console.error("Error al agregar la fila a la tabla:", error);
        }
    }

    // Asignar eventos una vez generada toda la tabla
    asignarEventosCantidad();
    actualizarTotalGeneral();
}
function verificarImagenAsync(url) {
    return new Promise((resolve) => {
        const img = new Image();
        img.onload = () => resolve(true);
        img.onerror = () => resolve(false);
        img.src = url;
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

    let nuevaCantidad = parseInt(input.value);
    if (isNaN(nuevaCantidad) || nuevaCantidad < 0) nuevaCantidad = 0;

    // Formatear y asignar al input
    nuevaCantidad = parseInt(nuevaCantidad);
    input.value = nuevaCantidad;

    // Actualizar en el objeto de sessionStorage
    productos[index].cantidad = parseInt(nuevaCantidad);
    productos[index].total = parseInt(nuevaCantidad) * parseFloat(productos[index].precio);

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
    const lblCupo = document.getElementById("lbl_cupoSaldo");//Saldo a la fecha
    const cupoDisponible = parseFloat(lblCupo?.textContent || 0);

    if (totalGeneral > cupoDisponible) {
        const excedido = (totalGeneral - cupoDisponible).toFixed(N2decimal);
        mostrarAlertaCupo(`Has sobrepasado el cupo disponible en <strong>$${excedido}</strong>.`, "danger");
        deshabilitarBotonGuardar();

    } else if (totalGeneral === cupoDisponible && cupoDisponible > 0) {
        mostrarAlertaCupo(`Has alcanzado exactamente tu cupo disponible.`, "warning");
        habilitarBotonGuardar();

    } else if (cupoDisponible > 0) {
        const restante = (cupoDisponible - totalGeneral).toFixed(N2decimal);
        mostrarAlertaCupo(`Tienes un cupo disponible de <strong>$${restante}</strong>.`, "info");
        habilitarBotonGuardar();
        
    } else {
        // Sin cupo disponible
        mostrarAlertaCupo(`No tienes cupo disponible para realizar pedidos.`, "danger");
        deshabilitarBotonGuardar();
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
    let tiendaSeleccionada = cmbTienda ? cmbTienda.value : "0";

    if (accion === "Create") {
        if (!tiendaSeleccionada || tiendaSeleccionada === "0") {
            mostrarAlertaCupo("Debe seleccionar una tienda antes de guardar.", "warning");
            return;
        }
    } else {
        // Si es edición, obtenemos el ID de la tienda del campo oculto
        tiendaSeleccionada = document.querySelector('#txth_tie_id').value;
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

    // Validar cupo disponible
    const cupoDisponible = parseFloat(document.getElementById("lbl_cupoSaldo").textContent) || 0;

    if (cupoDisponible <= 0) {
        mostrarAlertaCupo("No tiene cupo disponible. No se puede guardar el pedido.", "danger");
        return;
    }

    if (totalGeneral > cupoDisponible) {
        const excedido = (totalGeneral - cupoDisponible).toFixed(N2decimal);
        mostrarAlertaCupo(`No se puede guardar. El total supera el cupo disponible en $${excedido}.`, "danger");
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
    deshabilitarBotonGuardar();
    peticionAjaxSSL(url, metodo, dataPost, function (data) {
        // Manejar el éxito de la solicitud aquí
        if (data.status) {  
            swal("Pedido N°: " + data.numero, data.msg, "success");
            deshabilitarBotonGuardar();
            if (accion === 'Create') {
                limpiarDatosTienda();
                limpiarCombotienda();
            } else {
                // Si es edición, actualizar el campo oculto de tienda
                limpiarTablaProductos();
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }

            
        } else {
            swal("Atención", data.msg, "error");
            habilitarBotonGuardar();
        }

    }, function (jqXHR, textStatus, errorThrown) {
        // Manejar el error de la solicitud aquí
        console.log('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
        swal("Error", "Error al procesar el pedido", "error");
    });
}

function limpiarCombotienda() {
    const cmbTienda = document.getElementById("cmb_tienda");
    if (cmbTienda) {
        cmbTienda.value = "0";
        if (typeof $(cmbTienda).selectpicker === 'function') {
            $(cmbTienda).selectpicker('refresh');
        }
    }
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
        text: "¿Realmente quiere autorizar el registro?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, Autorizar!",
        cancelButtonText: "No, Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true,
        showLoaderOnConfirm: true // muestra un loader nativo de sweetalert
    }, function (isConfirm) {
        if (!isConfirm) return;

        // 🔒 Evitar múltiples clics deshabilitando el botón
        const confirmButton = document.querySelector('.confirm');
        if (confirmButton) confirmButton.disabled = true;

        const url = base_url + '/pedidoWeb/autorizarPedidoTemp';
        const metodo = 'POST';
        const dataPost = { ids };

        peticionAjaxSSL(url, metodo, dataPost, function (data) {
            // ✅ Éxito
            if (data.status) {
                swal({
                    title: "Autorizado!",
                    text: data.msg,
                    type: "success",
                    timer: 1500,
                    showConfirmButton: false
                });
                tableTienda.api().ajax.reload(null, false);
            } else {
                swal("Atención", data.msg, "error");
                if (confirmButton) confirmButton.disabled = false;
            }

        }, function (jqXHR, textStatus, errorThrown) {
            // ❌ Error
            console.error('Error en la solicitud:', textStatus, errorThrown);
            swal("Error", "Ocurrió un error al autorizar el pedido.", "error");
            if (confirmButton) confirmButton.disabled = false;
        });
    });
}


// Funciones auxiliares para manejar el estado de la interfaz
function deshabilitarBotonGuardar() {
    const btnGuardar = document.getElementById("btnGuardar");
    if (btnGuardar) {
        btnGuardar.disabled = true;
        btnGuardar.classList.add('disabled');
        btnGuardar.setAttribute('title', 'No se puede guardar: sin cupo disponible o sin tienda seleccionada');
    }
}

function habilitarBotonGuardar() {
    const btnGuardar = document.getElementById("btnGuardar");
    if (btnGuardar) {
        btnGuardar.disabled = false;
        btnGuardar.classList.remove('disabled');
        btnGuardar.removeAttribute('title');
    }
}

function limpiarDatosTienda() {
    $('#lbl_cupo').text('0.00');
    $('#lbl_contacto').text('');
    $('#lbl_direccion').text('');
    $('#lbl_telefono').text('');
    $('#lbl_cupoSaldo').text('0.00');
    $('#lbl_cupoUsado').text('0.00');
    limpiarTablaProductos();
    ocultarAlertaCupo();
}

function limpiarTablaProductos() {
    const tbody = document.querySelector(`#${tGrid} tbody`);
    if (tbody) {
        tbody.innerHTML = "";
    }
    sessionStorage.removeItem(storageKey);
    
    // Limpiar total general
    const lblTotalGeneral = document.getElementById("lblTotalGeneral");
    if (lblTotalGeneral) {
        lblTotalGeneral.textContent = "Total General: 0.00";
    }
}

// Si se abre la pantalla en modo "Nuevo" o "Actualizar", validar y cargar items según el botón.
document.addEventListener('DOMContentLoaded', function () {
    const btnText = document.getElementById('btnText');
    modoAccion = btnText ? btnText.textContent.trim().toLowerCase() : '';
    if (modoAccion === 'guardar') {
        limpiarDatosTienda();
        deshabilitarBotonGuardar();

    }else if (modoAccion === 'actualizar') {

    }

});





