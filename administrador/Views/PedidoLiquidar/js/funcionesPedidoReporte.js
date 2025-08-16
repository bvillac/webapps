let tableReporte;

document.addEventListener('DOMContentLoaded', function () {
    tableReporte = $('#tableReporteLiquidar').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/PedidoLiquidar/consultarDatosReporte",
            "dataSrc": "",
            "type": 'POST',
            "data": function (d) {
                d.fecha_inicio = $('#filtroFechaInicio').val();
                d.fecha_fin = $('#filtroFechaFin').val();
                d.tienda = $('#filtroTienda').val();
                d.cliente = $('#filtroCliente').val();
            }
        },
        "columns": [
            { "data": "Tienda" },
            { "data": "codigo" },
            { "data": "nombre" },
            { "data": "can_ped" },
            { "data": "t_venta" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0, 1, 2] },
            { 'className': "textcenter", "targets": [3, 4] }
        ],
        'dom': 'lBfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger'
            },
            // {
            //     extend: 'csvHtml5',
            //     text: '<i class="fas fa-file-csv"></i> CSV',
            //     titleAttr: 'Exportar a CSV',
            //     className: 'btn btn-info'
            // }
        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": numPaginado,//Numero Items Retornados
        "order": [[1, orderBy]]  //Orden por defecto 1 columna
    });

    // Botón Filtrar
    $('#btnFiltrar').on('click', function () {
        let cliente = $('#filtroCliente').val();
        if (cliente == null || cliente === '0') {
            swal('Falta seleccionar cliente', 'Debe seleccionar un cliente antes de filtrar.', "warning");
            return;
        }
        tableReporte.ajax.reload();
    });

    $('#filtroCliente').selectpicker();

    $('#filtroCliente').change(function () {
        const $cmbCliente = $('#filtroCliente');
        const clienteId = $cmbCliente.val();
        if (clienteId && clienteId !== '0') {
            fetchTiendas(clienteId);
        } else {
            swal('Error', 'Debe seleccionar un cliente', 'error');

        }
    });


});


function fetchTiendas(idsCliente) {
    const $cmbTienda = $('#filtroTienda');
    // Mostrar estado “cargando”
    $cmbTienda
        .prop('disabled', true)
        .empty()
        .append('<option value="0">CARGANDO...</option>');
        
    let url = base_url + '/Tienda/retornarTiendaporCliente';
    var metodo = 'POST';
    var datos = { ids: idsCliente };
    peticionAjaxSSL(url, metodo, datos, function (data) {
        if (data.status) {
            const tiendas = data.data;
            $cmbTienda
                .prop('disabled', false)
                .empty()
                .append('<option value="0">TODO</option>');

            tiendas.forEach(t => {
                $cmbTienda.append(
                    `<option value="${t.Ids}">${t.Nombre}</option>`
                );
            });
            $cmbTienda.selectpicker('refresh');

        } else {
            swal('Error', 'No se pudieron cargar las tiendas', 'error');
        }

    }, function (jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error:', textStatus, errorThrown);
        swal('Error', 'No se pudieron cargar las tiendas', 'error');
    });


}