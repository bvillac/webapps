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

		$("#btn_login").click(function () {
			iniciarSession();

		});
	});




}, false);

function iniciarSession() {
	let strEmail = document.querySelector('#txt_Email').value;
	let strPassword = document.querySelector('#txt_clave').value;

	if (strEmail == "" || strPassword == "") {
		swal("Por favor", "Ingrese usuario y clave.", "error");
		return false;
	} else {
		let url = base_url + '/Login/loginUsuario';
		var metodo = 'POST';
		var datos = { txt_Email: strEmail, txt_clave: strPassword };
		peticionAjaxSSL(url, metodo, datos, function (data) {
			// Manejar el éxito de la solicitud aquí
			if (data.status) {
				alert("redireciona: "+base_url+'/loginPedido/loginEmpresa');
				window.location = base_url + '/loginPedido/loginEmpresa';
				//window.location.reload(false);
			} else {
				swal("Atención", data.msg, "error");
				document.querySelector('#txt_clave').value = "";
			}

		}, function (jqXHR, textStatus, errorThrown) {
			// Manejar el error de la solicitud aquí
			console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
		});

	}
}