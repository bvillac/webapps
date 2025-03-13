document.addEventListener("DOMContentLoaded", function () {
    const tGrid = "TbG_Tiendas";
    const storageKey = "dts_precioTienda";
    const btnAgregar = $("#btnAgregar");
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
        return JSON.parse(localStorage.getItem(storageKey)) || [];
    }

    function guardarProductosEnStorage(productos) {
        localStorage.setItem(storageKey, JSON.stringify(productos));
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

    function eliminarProducto(id) {
        let productos = obtenerProductosGuardados();
        productos = productos.filter(p => p.ART_ID !== id);//crea un nuevo array que excluye el producto cuyo ART_ID coincida con el id recibido.
        guardarProductosEnStorage(productos);
        actualizarTabla();

        // Petición AJAX para eliminar en el servidor
        /*fetch("/Tienda/eliminarProducto", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ ART_ID: id })
        }).then(res => res.json())
          .then(data => alert(data.msg))
          .catch(err => console.error("Error al eliminar:", err));*/
    }

    function actualizarTabla() {
        const tbody = document.querySelector(`#${tGrid} tbody`);
        tbody.innerHTML = "";
        const productos = obtenerProductosGuardados();

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
                    <button class="btn-delete" data-id="${producto.ART_ID}">
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

        // Evento para actualizar el precio en `localStorage`
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

    function guardarEnServidor() {
        const productos = obtenerProductosGuardados();

        fetch(base_url+"/Tienda/guardarListaProductos", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ productos })
        }).then(res => res.json())
          .then(data => alert(data.msg))
          .catch(err => console.error("Error al guardar:", err));
    }

    document.getElementById("btnAgregar").addEventListener("click", agregarProducto);
    document.getElementById("btnGuardar").addEventListener("click", guardarEnServidor);

    actualizarTabla();
});
