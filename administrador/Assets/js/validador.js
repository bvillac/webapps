// Función genérica para validar el valor ingresado en un campo de entrada
function validarCampoBlur(input, tipoValidacion,nDecimal=2) {
    input.blur(function (e) {
        e.preventDefault();  // Prevenir la acción por defecto del evento

        let valor = $(this).val().trim();  // Obtenemos el valor ingresado y eliminamos espacios en blanco

        // Validación genérica
        if (tipoValidacion === 'decimal') {
            valor = parseFloat(valor);  // Convertimos a número decimal
            if (isNaN(valor) || valor <= 0) {
                $(this).val(0);  // Si no es un número válido o es menor o igual a 0, restablecemos a 0
            } else {
                $(this).val(redondea(valor, nDecimal));  // Redondeamos al número de decimales
            }
        } else if (tipoValidacion === 'texto') {
            if (!esTexto(valor)) {  // Validación de texto (solo letras y espacios)
                $(this).val("");  // Limpiar el campo si no es válido
                $(this).addClass('is-invalid');  // Agregar clase de error
            } else {
                $(this).removeClass('is-invalid');  // Eliminar clase de error
            }
        } else if (tipoValidacion === 'entero') {
            if (!esEntero(valor)) {  // Validación de enteros (solo números enteros)
                $(this).val("");  // Limpiar el campo si no es un número entero válido
                $(this).addClass('is-invalid');  // Agregar clase de error
            } else {
                $(this).removeClass('is-invalid');  // Eliminar clase de error
            }
        } else if (tipoValidacion === 'email') {
            if (!esEmail(valor)) {  // Validación de correo electrónico
                $(this).val("");  // Limpiar el campo si no es un correo válido
                $(this).addClass('is-invalid');  // Agregar clase de error
            } else {
                $(this).removeClass('is-invalid');  // Eliminar clase de error
            }
        }

    });
}

// Uso de la función en el campo txt_valor para validar números decimales
//validarCampoBlur($("#txt_valor"), 'decimal');
// Uso de la función en otros campos para validar texto, enteros, o correos electrónicos
//validarCampoBlur($("#txt_nombre"), 'texto');
//validarCampoBlur($("#txt_entero"), 'entero');
//validarCampoBlur($("#txt_email"), 'email');

// Funciones auxiliares para validaciones
function esTexto(txtString) {
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    return stringText.test(txtString);
}

function esEntero(intCant) {
    var intCantidad = new RegExp(/^([0-9])*$/);
    return intCantidad.test(intCant);
}

function esEmail(email) {
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})$/);
    return stringEmail.test(email);
}

// Redondeo de número
function redondea(valor, decimales) {
    return valor.toFixed(decimales);  // Redondea el valor a los decimales especificados
}

function validarNumeroYPunto(event, nextElementId) {
    //validarNumeroYPunto(event, "txt_otroCampo"); como usar
    // Obtener el código de la tecla presionada
    let key = event.key;
    
    // Permitir solo números (0-9) y punto (.)
    if (!/[\d.]/.test(key) && key !== "Backspace" && key !== "Delete" && key !== "Tab") {
        event.preventDefault(); // Evita la acción si la tecla no es válida
    }
    
    // Si presionan Enter, mover al siguiente campo de entrada
    if (key === "Enter") {
        event.preventDefault(); // Evitar que el formulario se envíe si es un campo de formulario
        const nextElement = document.getElementById(nextElementId);
        if (nextElement) {
            nextElement.focus(); // Cambiar el foco al siguiente elemento
        }
    }
}


