

//Bloque todas las teclas y permite el ingrso de numeros
function controlTagEvent(e) {
    let tecla = e.key || String.fromCharCode(e.keyCode || e.which);

    // Permitir teclas especiales (Backspace, Tab)
    if (["Backspace", "Tab"].includes(tecla)) return true;

    // Expresión regular para solo permitir números y espacios
    return /^[0-9\s]$/.test(tecla);
}


//Validaacion de Testos sin simbolo (Nombres apellidos)
function esTexto(texto) {
    return /^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/.test(texto.trim());
}


//Validar Datos que seran enteros numericos
function esEntero(valor) {
    return /^\d+$/.test(valor.trim());
}


//Validar Datos que seran decimales
function validarDecimal(input) {
    const regex = /^\d+(\.\d+)?$/;
    return regex.test(input.value.trim());
}


//Verificar si es Email
function esEmail(email){
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})$/);
    if (stringEmail.test(email) == false){
        return false;
    }else{
        return true;
    }
}

function validarTexto() {
    document.querySelectorAll(".validarTexto").forEach((input) => {
        input.addEventListener("input", function () {
            // Elimina caracteres que no sean letras o espacios
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");

            // Agrega o quita la clase de error según la validación
            if (this.value.trim() === "") {
                this.classList.add("is-invalid");
            } else {
                this.classList.remove("is-invalid");
            }
        });
    });
}

function validarNumber() {
    document.querySelectorAll(".validarNumber").forEach((input) => {
        input.addEventListener("input", function () {
            // Elimina caracteres no numéricos
            this.value = this.value.replace(/[^0-9]/g, "");

            // Agrega o quita la clase de error según la validación
            if (this.value.trim() === "" || isNaN(this.value)) {
                this.classList.add("is-invalid");
            } else {
                this.classList.remove("is-invalid");
            }
        });
    });
}

function validarEmail() {
    document.querySelectorAll(".validarEmail").forEach((input) => {
        input.addEventListener("input", function () {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(this.value.trim())) {
                this.classList.add("is-invalid");
            } else {
                this.classList.remove("is-invalid");
            }
        });
    });
}

window.addEventListener('load', function() {
	validarTexto();
	validarEmail(); 
	validarNumber();
}, false);