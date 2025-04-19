$(document).ready(function () {
	
	$("#btn_login").click(function () {
		iniciarSessionPedido();

	});
});





function iniciarSessionPedido() {
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
				window.location = base_url + '/LoginTienda';
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