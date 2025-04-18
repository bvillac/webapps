$(document).ready(function () {
	$('#cmb_empresa').on('change', function(){
		cargarTienda(this.value);
	});
	$("#btn_login").click(function () {
		iniciarSessionEmpresa();

	});
});


function cargarTienda(ids) {
	if (ids != 0) {
		let url = base_url + '/LoginTienda/bucarTiendas';
		var metodo = 'POST';
		var datos = { Ids: ids };
		peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
			// Manejar el éxito de la solicitud aquí
			if (data.status) {
				fntDataTienda(data.data);
			} else {
				swal("Atención", data.msg, "error");
			}
		}, function (jqXHR, textStatus, errorThrown) {
			// Manejar el error de la solicitud aquí
			console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
		});
	} else {
		$("#cmb_centro").prop("disabled", true);
		swal("Información", "Seleccionar un Empresa", "info");
	}

}

function fntDataTienda(ObjData) {
	$("#cmb_punto").html('<option value="0">SELECCIONAR</option>');
	$("#cmb_punto").prop("disabled", false);
	var result = ObjData;
	for (var i = 0; i < result.length; i++) {
		$("#cmb_punto").append(
			'<option value="' +	result[i].Ids +	'"  >' +
				result[i].Nombre +
			"</option>"
		);
		
	}
}


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