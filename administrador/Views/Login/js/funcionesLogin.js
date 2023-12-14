$('.login-content [data-toggle="flip"]').click(function () {
	$('.login-box').toggleClass('flipped');
	return false;
});

//var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {
	if (document.querySelector("#frm_Login")) {//Verificamos si existe el formulario
		let formLogin = document.querySelector("#frm_Login");
		//se crea el eventos onsumit al formulario
		formLogin.onsubmit = function (e) {
			e.preventDefault();

			let strEmail = document.querySelector('#txt_Email').value;
			let strPassword = document.querySelector('#txt_clave').value;

			if (strEmail == "" || strPassword == "") {
				swal("Por favor", "Ingrese usuario y clave.", "error");
				return false;
			} else {
				//divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url + '/Login/loginUsuario';
				var formData = new FormData(formLogin);
				request.open("POST", ajaxUrl, true);
				request.send(formData);
				request.onreadystatechange = function () {
					if (request.readyState != 4) return;
					if (request.status == 200) {
						var objData = JSON.parse(request.responseText);
						if (objData.status) {
							//window.location = base_url+'/dashboard';
							window.location.reload(false);
						} else {
							swal("Atención", objData.msg, "error");
							document.querySelector('#txt_clave').value = "";
						}
					} else {
						swal("Atención", "Error en el proceso", "error");
					}
					//divLoading.style.display = "none";
					return false;
				}
			}
		}
	}
	$(document).ready(function () {
		$('#mostrar').click(function () {
			//Comprobamos que la cadena NO esté vacía.
			if ($(this).hasClass('mdi-eye') && ($("#txt_clave").val() != "")) {
				$('#txt_clave').removeAttr('type');
				$('#mostrar').addClass('mdi-eye-off').removeClass('mdi-eye');
				$('.pwdtxt').html("Ocultar contraseña");
			}
			else {
				$('#txt_clave').attr('type', 'password');
				$('#mostrar').addClass('mdi-eye').removeClass('mdi-eye-off');
				$('.pwdtxt').html("Mostrar contraseña");
			}
		});
	});


	if (document.querySelector("#formRecetPass")) {
		let formRecetPass = document.querySelector("#formRecetPass");
		formRecetPass.onsubmit = function (e) {
			e.preventDefault();
			let strEmail = document.querySelector('#txt_Email_Reset').value;
			if (strEmail == "") {
				swal("Por favor", "Ingrese su correo electrónico.", "error");
				return false;
			} else {
				//divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ?
					new XMLHttpRequest() :
					new ActiveXObject('Microsoft.XMLHTTP');

				var ajaxUrl = base_url + '/Login/cambiarClave';
				var formData = new FormData(formRecetPass);
				request.open("POST", ajaxUrl, true);
				request.send(formData);
				request.onreadystatechange = function () {
					if (request.readyState != 4) return;
					if (request.status == 200) {
						var objData = JSON.parse(request.responseText);
						if (objData.status) {
							swal({
								title: "",
								text: objData.msg,
								type: "success",
								confirmButtonText: "Aceptar",
								closeOnConfirm: false,
							}, function (isConfirm) {
								if (isConfirm) {
									window.location = base_url;//Retorna al Portal Principal
								}
							});
						} else {
							swal("Atención", objData.msg, "error");
						}
					} else {
						swal("Atención", "Error en el proceso", "error");
					}
					divLoading.style.display = "none";
					return false;
				}
			}
		}
	}

	if (document.querySelector("#formCambiarPass")) {
		let formCambiarPass = document.querySelector("#formCambiarPass");
		formCambiarPass.onsubmit = function (e) {
			e.preventDefault();

			let strPassword = document.querySelector('#txtPassword').value;
			let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;
			let idUsuario = document.querySelector('#idUsuario').value;

			if (strPassword == "" || strPasswordConfirm == "") {
				swal("Por favor", "Escribe la nueva clave.", "error");
				return false;
			} else {
				if (strPassword.length < 5) {
					swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres.", "info");
					return false;
				}
				if (strPassword != strPasswordConfirm) {
					swal("Atención", "Las contraseñas no son iguales.", "error");
					return false;
				}
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ?
					new XMLHttpRequest() :
					new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url + '/Login/setPassword';
				var formData = new FormData(formCambiarPass);
				request.open("POST", ajaxUrl, true);
				request.send(formData);
				request.onreadystatechange = function () {
					if (request.readyState != 4) return;
					if (request.status == 200) {
						var objData = JSON.parse(request.responseText);
						if (objData.status) {
							swal({
								title: "",
								text: objData.msg,
								type: "success",
								confirmButtonText: "Iniciar sessión",
								closeOnConfirm: false,
							}, function (isConfirm) {
								if (isConfirm) {
									window.location = base_url + '/login';
								}
							});
						} else {
							swal("Atención", objData.msg, "error");
						}
					} else {
						swal("Atención", "Error en el proceso", "error");
					}
					divLoading.style.display = "none";
				}
			}
		}
	}

}, false);