document.addEventListener("DOMContentLoaded", function () {
    const tGrid = "TbG_Tiendas";
    const storageKey = "dts_precioTienda";
    const btnAgregar = document.getElementById("btnAgregar");
    const btnGuardar = document.getElementById("btnGuardar");
    const dataGrid = document.getElementById("TbG_Tiendas");
    const txtPrecio = $("#txt_PrecioProducto");

    $("#txt_CodigoProducto").autocomplete({
        source: async function (request, response) {
            try {
                const link = `${base_url}/Tienda/buscarAutoProducto`;
    
                const res = await fetch(link, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ parametro: request.term, limit: 10 })
                });
    
                const data = await res.json();
    
                if (data.status) {
                    const arrayList = data.data.map(objeto => ({
                        label: `${objeto.cod_art} - ${objeto.art_des_com}`,
                        value: objeto.art_des_com,
                        cod_art: objeto.cod_art, // Guardamos el código del producto
                        id: objeto.art_id,
                        //p_venta: parseFloat(objeto.pcli_p_venta).toFixed(2) // Precio con dos decimales
                    }));
                    response(arrayList);
                } else {
                    //limpiarAutocompletar();
                    swal("Atención!", data.msg, "info");
                }
            } catch (error) {
                console.error("Error en la búsqueda:", error);
                swal("Error!", "No se pudo obtener los datos.", "error");
            }
        },
        minLength: minLengthGeneral,
        select: function (event, ui) {
            $('#txth_art_id').val(ui.item.id);
            $("#txth_cod_art").val(ui.item.cod_art);
            txtPrecio.focus();
        }
    });
    

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
        document.getElementById("txt_PrecioProducto").value = "";
    }

    function agregarProducto() {
        const art_Id = parseInt(document.getElementById("txth_art_id").value);//$("#txth_art_id").val();
        const codigo = $("#txth_cod_art").val();
        const nombre = document.getElementById("txt_CodigoProducto").value;
        const precio = parseFloat(document.getElementById("txt_PrecioProducto").value) || 0;
        
        if (!nombre) {
            alert("Ingrese un nombre de producto");
            return;
        }

        let productos = obtenerProductosGuardados();

        // Verificar si el producto ya existe
        if (productos.some(p => p.ART_DES_COM === nombre)) {
            alert("El producto ya está en la lista");
            return;
        }

        // Crear el nuevo producto
        const nuevoProducto = {
            ART_ID: art_Id,//Date.now(), // Usamos timestamp como ID único
            COD_ART: codigo,//nombre.substring(0, 5).toUpperCase(),
            ART_DES_COM: nombre,
            ART_P_VENTA: precio.toFixed(2),
        };

        productos.push(nuevoProducto);
        guardarProductosEnStorage(productos);
        actualizarTabla();
        limpiarCampos();
    }

    async function eliminarProducto(id) {
        const idsCliente = $('#txth_ids').val()?.trim(); // Elimina espacios en blanco
        if (!idsCliente) {
            swal("Error", "No se encontró el cliente asociado.", "error");
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
                <td>${producto.COD_ART}</td>
                <td>${producto.ART_DES_COM}</td>
                <td>
                    <input type="number" value="${producto.ART_P_VENTA}" min="0" step="0.01"
                        data-id="${producto.ART_ID}" class="precio-input" 
                        onblur="javascript:return formatearDecimal(this,N2decimal)"  />
                </td>
                <td>
                    <button class="btn-delete" data-id="${producto.pcli_id}">
                        <img src="delete-icon.png" alt="Eliminar" width="20" />
                    </button>
                </td>
            `;

            tbody.appendChild(row);
        });

        // Agregar eventos a los botones de eliminar
        document.querySelectorAll(".btn-delete").forEach(btn => {
            btn.addEventListener("click", function () {
                const id = parseInt(this.getAttribute("data-id"));
                eliminarProducto(id);
            });
        });

        // Evento para actualizar el precio en `sessionStorage`
        document.querySelectorAll(".precio-input").forEach(input => {
            input.addEventListener("change", function () {
                const id = parseInt(this.getAttribute("data-id"));
                let productos = obtenerProductosGuardados();
                productos = productos.map(p => p.ART_ID === id ? { ...p, ART_P_VENTA: this.value } : p);
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
    

    if (btnAgregar) {  // Verifica si el botón existe
        btnAgregar.addEventListener("click", agregarProducto);
    }
    if (btnGuardar) {  // Verifica si el botón existe
        btnGuardar.addEventListener("click", guardarEnServidor);
    }
    
    if (dataGrid) {  // Verifica si el botón existe
        actualizarTabla();
    }
    
});
