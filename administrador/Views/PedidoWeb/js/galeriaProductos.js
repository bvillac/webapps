document.addEventListener("DOMContentLoaded", function () {
    const tGrid = "TbG_Tiendas";
    const storageKey = "dts_precioTienda";
    const btnGuardar = document.getElementById("btn_GuardarTienda");
    const dataGrid = document.getElementById("TbG_Tiendas");
    

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
    



    async function guardarEnServidor() {
        //const productos = obtenerProductosGuardados();
        const productosCheck = JSON.parse(sessionStorage.getItem("seleccionados")) || [];

        //const idsCliente = $('#txth_ids').val()?.trim(); // Elimina espacios en blanco
        const tienda_id = $('#cmb_tiendas').val();
        const accion = "Create";
    
        // Verificar si idsCliente tiene un valor válido antes de continuar
        if (tienda_id == '0') {
            swal("Atención", "Debe seleccinar una tienda.", "error");
            return;
        }
    
        try {
            $("#btn_GuardarTienda").prop("disabled", true); // Deshabilita el botón mientras se guarda
    
            const response = await fetch(base_url + "/tienda/guardarListaProductosTienda", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ productosCheck, tienda_id, accion })
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
            $("#btn_GuardarTienda").prop("disabled", false); // Habilita el botón nuevamente
        }
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

function verificarImagen(url, callback) {
    let img = new Image();
    img.src = url;
    img.onload = () => callback(true);  // La imagen existe
    img.onerror = () => callback(false); // La imagen no existe
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

