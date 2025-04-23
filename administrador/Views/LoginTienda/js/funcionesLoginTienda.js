$(document).ready(function () {
	$('#cmb_cliente').on('change', function(){
		cargarTienda(this.value);
	});

	$("#btn_login").click(function () {
		iniciarSessionTienda();

	});
	$("#btn_loginPedido").click(function () {
		window.location = base_url+'/LoginPedido';

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
		$("#cmb_tienda").prop("disabled", true);
		swal("Información", "Seleccionar un Empresa", "info");
	}

}

function fntDataTienda(ObjData) {
	$("#cmb_tienda").html('<option value="0">SELECCIONAR</option>');
	$("#cmb_tienda").prop("disabled", false);
	var result = ObjData.Tienda;
	for (var i = 0; i < result.length; i++) {
		$("#cmb_tienda").append(
			'<option value="' +	result[i].Ids +	'"  >' +
				result[i].Nombre +
			"</option>"
		);
		
	}
}



function iniciarSessionTienda() {
	let ncliente = $("#cmb_cliente").val();
	let nTienda = $("#cmb_tienda").val();
	if (ncliente == 0 || nTienda == 0 ) {
		swal(
			"Atención",
			"Todos los datos son obligatorios.",
			"error"
		);
		return false;
	}

	let url = base_url + '/LoginTienda/loginUsuarioTienda';
	var metodo = 'POST';
	var datos = { Cliente: ncliente, Tienda: nTienda };
	peticionAjaxSSL(url, metodo, datos, function (data) {
		// Manejar el éxito de la solicitud aquí
		if (data.status) {
			//Hace un refress del sitio
			window.location.reload(false);
			//window.location.reload(true);
		} else {
			swal("Atención", data.msg, "error");
			
		}

	}, function (jqXHR, textStatus, errorThrown) {
		// Manejar el error de la solicitud aquí
		console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
	});

}