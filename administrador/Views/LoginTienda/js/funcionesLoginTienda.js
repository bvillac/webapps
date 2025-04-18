$(document).ready(function () {
	$('#cmb_establecimiento').on('change', function(){
		fntPunto(this.value);
	});
	$("#btn_login").click(function () {
		iniciarSessionEmpresa();

	});
});

function fntCentro(ids) {
	//$("#cmb_centro").html('<option value="0">SELECCIONAR</option>');
	if (ids != 0) {
		let url = base_url + '/LoginEmpresa/bucarCentro';
		var metodo = 'POST';
		const datos = { Ids: ids };
		//EncriptAjax(url, metodo, datos, function (data) {
		peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
			// Manejar el éxito de la solicitud aquí
			if (data.status) {
				fntDataEstablecimiento(data.data);
				fntDataCentro(data.data);
				/*clearTimeout(delayTimer);
				delayTimer = setTimeout(function () {
					fntInstructor(ids);
				}, 500); // Retardo de 500 ms (medio segundo)*/

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

function fntDataCentro(ObjData) {
	$("#cmb_centro").html('<option value="0">SELECCIONAR</option>');
	$("#cmb_centro").prop("disabled", false);
	var result = ObjData.Centro;
	for (var i = 0; i < result.length; i++) {
		$("#cmb_centro").append(
			'<option value="' + result[i].Ids +	'" >' +
				result[i].Nombre +
			"</option>"
		);
	}
}

function fntDataEstablecimiento(ObjData) {
	$("#cmb_establecimiento").html('<option value="0">SELECCIONAR</option>');
	$("#cmb_establecimiento").prop("disabled", false);
	var result = ObjData.Establecimiento;
	for (var i = 0; i < result.length; i++) {
		$("#cmb_establecimiento").append(
			'<option value="' +	result[i].Ids +	'"  >' +
				result[i].Nombre +
			"</option>"
		);
		
	}
}

function fntPunto(ids) {
	if (ids != 0) {
		let url = base_url + '/LoginEmpresa/bucarPunto';
		var metodo = 'POST';
		var datos = { Ids: ids };
		peticionAjax(url, metodo, { datos: btoa(JSON.stringify(datos)) }, function (data) {
			// Manejar el éxito de la solicitud aquí
			if (data.status) {
				fntDataPunto(data.data);
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

function fntDataPunto(ObjData) {
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


function iniciarSessionEmpresa() {
	let nEmpresa = $("#cmb_empresa").val();
	let nEstablecimiento = $("#cmb_establecimiento").val();
	let nPunto = $("#cmb_punto").val();
	let nCentro = $("#cmb_centro").val();
	if (nEmpresa == 0 || nEstablecimiento == 0 || nPunto == 0) {
		swal(
			"Atención",
			"Todos los datos son obligatorios.",
			"error"
		);
		return false;
	}

	let url = base_url + '/LoginEmpresa/loginUsuarioEmpresa';
	var metodo = 'POST';
	var datos = { Empresa: nEmpresa, Establecimiento: nEstablecimiento, Punto: nPunto, Centro: nCentro };
	peticionAjaxSSL(url, metodo, datos, function (data) {
		// Manejar el éxito de la solicitud aquí
		if (data.status) {
			//Hace un refress del sitio
			window.location.reload(false);
		} else {
			swal("Atención", data.msg, "error");
			
		}

	}, function (jqXHR, textStatus, errorThrown) {
		// Manejar el error de la solicitud aquí
		console.error('Error en la solicitud. Estado:', textStatus, 'Error:', errorThrown);
	});

}