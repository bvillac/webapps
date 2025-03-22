document.addEventListener("DOMContentLoaded", function () {
    const tGrid = "TbG_Tiendas";
    const storageKey = "dts_precioTienda";
    const btnAgregar = document.getElementById("btnAgregar");
    const btnGuardar = document.getElementById("btnGuardar");
    const dataGrid = document.getElementById("TbG_Tiendas");
    const txtPrecio = document.getElementById("txt_PrecioProducto");

    


    

    function obtenerProductosGuardados() {
        return JSON.parse(sessionStorage.getItem(storageKey)) || [];
    }

    function guardarProductosEnStorage(productos) {
        sessionStorage.setItem(storageKey, JSON.stringify(productos));
    }

    function limpiarCampos() {
        document.getElementById("txth_art_id").value = "0";
        document.getElementById("txth_cod_art").value = "";
        document.getElementById("txt_CodigoProducto").value = "";
        document.getElementById("txt_PrecioProducto").value = "0.00";
        document.getElementById("txth_i_m_iva").value = "0";
    }

    function agregarProducto() {
        const art_Id = parseInt(document.getElementById("txth_art_id").value);//$("#txth_art_id").val();
        const codigo = $("#txth_cod_art").val();
        const nombre = document.getElementById("txt_CodigoProducto").value.trim();
        const precio = parseFloat(document.getElementById("txt_PrecioProducto").value) || 0;
        const i_m_iva = parseFloat(document.getElementById("txth_i_m_iva").value) || 0;
        const por_des = (0).toFixed(N2decimal);
        const val_des = (0).toFixed(N2decimal);

        // Validar si el precio ingresado es un número decimal válido
        if (isNaN(precio) || precio <= 0) {
            swal("Error", "Por favor ingrese un precio válido mayor a 0.", "error");
            return;
        }
        
        if (!nombre) {
            swal("Info", "Ingrese un nombre de producto.", "info");
            return;
        }

        let productos = obtenerProductosGuardados();

        // Verificar si el producto ya existe
        if (productos.some(p => p.des_com === nombre)) {
            swal("Info", "El producto ya está en la lista.", "info");
            return;
        }

        // Crear el nuevo producto
        const nuevoProducto = {
            art_id: art_Id,//Date.now(), // Usamos timestamp como ID único
            cod_art: codigo,//nombre.substring(0, 5).toUpperCase(),
            des_com: nombre,
            p_venta: precio.toFixed(N2decimal),
            i_m_iva: i_m_iva,
            por_des: por_des,
            val_des: val_des,
        };

        productos.push(nuevoProducto);
        guardarProductosEnStorage(productos);
        actualizarTabla();
        limpiarCampos();
    }

    async function eliminarProducto(id) {
        const idsCliente = $('#txth_ids').val()?.trim(); // Elimina espacios en blanco
        if (!idsCliente) {
            swal("Error", "No se encontró el Registro.", "error");
            return;
        }

        try {
            // Realiza la petición POST para guardar los productos
            const response = await fetch(base_url + "/ClientePedido/eliminarItemCliente", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ ids:id, idsCliente, accion: "Delete" }) // Se podría agregar una acción "Delete"
            });
    
            const data = await response.json();
    
            if (data.status) {
                 // Obtiene los productos guardados y elimina el producto con el pcli_id coincidente
                let productos = obtenerProductosGuardados();
                productos = productos.filter(p => p.pcli_id !== id); // Crea un nuevo array excluyendo el producto
            
                // Guarda el nuevo array de productos en sessionStorage
                guardarProductosEnStorage(productos);
            
                // Actualiza la tabla de productos visualmente
                actualizarTabla();
                swal("Éxito", data.msg, "success");
                // Si deseas redirigir, puedes descomentar la siguiente línea
                // window.location = base_url + '/clientePedido';
            } else {
                swal("Error", data.msg, "error");
            }
        } catch (err) {
            console.error("Error al guardar:", err);
            swal("Error", "Hubo un problema al guardar los productos.", "error");
        } finally {
            $("#btnGuardar").prop("disabled", false); // Habilita el botón nuevamente
        }
    }
    

    function actualizarTabla() {
        const tbody = document.querySelector(`#${tGrid} tbody`);
        tbody.innerHTML = "";
        const productos = obtenerProductosGuardados();
        //productos.length>0
        productos.forEach((producto, index) => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td><input type="checkbox" class="row-check" data-id="${producto.art_id}"></td>
                <td>${producto.cod_art}</td>
                <td>${producto.des_com}</td>
                <td>
                    <img src="/imagenes/A0002_G-01.jpg" alt="Producto" width="50" 
                        class="img-thumbnail" onclick="abrirGaleria(['A0002_G-01.jpg', 'A0002_G-02.jpg', 'A0002_G-03.jpg'])">
                </td>
                
            `;

            tbody.appendChild(row);
        });

        // Agregar eventos a los botones de eliminar
        document.querySelectorAll(".btn-delete").forEach(btn => {
            btn.addEventListener("click", function () {
                const id = parseInt(this.getAttribute("data-id"));
                //eliminarProducto(id);
                eliminarItemGrid(id);
            });
        });

        // Evento para actualizar el precio en `sessionStorage`
        document.querySelectorAll(".precio-input").forEach(input => {
            input.addEventListener("change", function () {
                const id = parseInt(this.getAttribute("data-id"));
                let productos = obtenerProductosGuardados();
                productos = productos.map(p => p.art_Id === id ? { ...p, p_venta: this.value } : p);
                guardarProductosEnStorage(productos);
            });
            //input.addEventListener("blur", formatearPrecio);
            input.addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    this.blur(); // Quita el foco después de presionar Enter
                }
            });
        });

        
    }

    async function guardarEnServidor() {
        const productos = obtenerProductosGuardados();
        const idsCliente = $('#txth_ids').val()?.trim(); // Elimina espacios en blanco
        const accion = "Create";
    
        // Verificar si idsCliente tiene un valor válido antes de continuar
        if (!idsCliente) {
            console.warn("ID de cliente no válido o vacío.");
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return;
        }
    
        try {
            $("#btnGuardar").prop("disabled", true); // Deshabilita el botón mientras se guarda
    
            const response = await fetch(base_url + "/ClientePedido/guardarListaProductos", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ productos, idsCliente, accion })
            });
    
            const data = await response.json();
    
            if (data.status) {
                swal("Éxito", data.msg, "success");
                //window.location = base_url + '/clientePedido';
            } else {
                swal("Error", data.msg, "error");
            }
        } catch (err) {
            console.error("Error al guardar:", err);
            swal("Error", "Hubo un problema al guardar los productos.", "error");
        } finally {
            $("#btnGuardar").prop("disabled", false); // Habilita el botón nuevamente
        }
    }


    $("#txt_PrecioProducto").blur(function () {
        validarCampoBlur($(this), 'decimal',N2decimal);
    });

    $("#txt_PrecioProducto").keypress(function (e) {
        validarNumeroYPunto(e,"btnAgregar");
    });

    /*$("#txt_PrecioProducto").keyup(function (e) {
        validarNumeroYPunto(e,"btnAgregar");
    });*/

    function eliminarItemGrid(ids) {
        swal({
            title: "Eliminar Registro",
            text: "¿Realmente quiere eliminar el Registro?",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
    
            if (isConfirm) {
                eliminarProducto(ids);
            }
    
        });
    
    }
    
 

    if (btnGuardar) {  // Verifica si el botón existe
        btnGuardar.addEventListener("click", guardarEnServidor);
    }
    
    if (dataGrid) {  // Verifica si el botón existe
        actualizarTabla();
    }


    
    
});


function toggleSelectAll(checkbox) {
    let checkboxes = document.querySelectorAll(".row-check");
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        almacenarSeleccion(cb.dataset.id, checkbox.checked);
    });
}



// Función para almacenar en sessionStorage los checkboxes seleccionados
function almacenarSeleccion(id, isChecked) {
    let seleccionados = JSON.parse(sessionStorage.getItem("seleccionados")) || [];
    
    if (isChecked) {
        if (!seleccionados.includes(id)) {
            seleccionados.push(id);
        }
    } else {
        seleccionados = seleccionados.filter(item => item !== id);
    }
    
    sessionStorage.setItem("seleccionados", JSON.stringify(seleccionados));
}

// Función para cargar los checkboxes seleccionados al recargar la página
function cargarSeleccionados() {
    let seleccionados = JSON.parse(sessionStorage.getItem("seleccionados")) || [];
    
    document.querySelectorAll(".row-check").forEach(cb => {
        if (seleccionados.includes(cb.dataset.id)) {
            cb.checked = true;
        }
        
        cb.addEventListener("change", function () {
            almacenarSeleccion(this.dataset.id, this.checked);
            actualizarSeleccionGlobal();
        });
    });
}

// Función para actualizar el estado del checkbox "Seleccionar todo"
function actualizarSeleccionGlobal() {
    let checkboxes = document.querySelectorAll(".row-check");
    let checkAll = document.getElementById("selectAll");

    let total = checkboxes.length;
    let seleccionados = Array.from(checkboxes).filter(cb => cb.checked).length;

    checkAll.checked = (seleccionados === total);
}

// Función para filtrar la tabla dinámicamente sin perder selección
function filtrarTabla() {
    let searchValue = document.getElementById("txtCodigoProducto").value.toLowerCase();
    let filas = document.querySelectorAll("#TbG_Tiendas tbody tr");

    filas.forEach(fila => {
        let textoFila = fila.innerText.toLowerCase();
        fila.style.display = textoFila.includes(searchValue) ? "" : "none";
    });
}

function abrirGaleria(imagenes) {
    let carouselInner = document.getElementById("carouselInner");
    carouselInner.innerHTML = "";

    imagenes.slice(0, 3).forEach((img, index) => {
        let activeClass = index === 0 ? "active" : "";
        //let imgPath = `/opt/webapps/productos/${img}`;
        let imgPath = `/imagenes/${img}`;

        let item = `
            <div class="carousel-item ${activeClass}">
                <img src="${imgPath}" class="d-block w-100 img-fluid" alt="Imagen">
            </div>
        `;
        carouselInner.innerHTML += item;
    });

    new bootstrap.Modal(document.getElementById("modalGaleria")).show();
}

