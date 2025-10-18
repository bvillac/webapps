let tableReporte;

document.addEventListener('DOMContentLoaded', function () {
    tableReporte = $('#tableReporteLiquidar').DataTable({
        processing: true,
        serverSide: true,
        language: { url: cdnTable },
        ajax: {
            url: base_url + "/PedidoLiquidar/consultarDatosReporte",
            type: 'POST',
            dataSrc: "",
            data: function (d) {
                d.fecha_inicio = $('#filtroFechaInicio').val();
                d.fecha_fin = $('#filtroFechaFin').val();
                d.tienda = $('#filtroTienda').val();
                d.cliente = $('#filtroCliente').val();
            }
        },
        columns: [
            { data: "Tienda" },
            { data: "codigo" },
            { data: "nombre" },
            { data: "can_ped" },
            { data: "t_venta" }
        ],
        columnDefs: [
            { className: "text-start", targets: [0, 1, 2] },
            {
                className: "text-end", targets: [3],
                render: function (data) {
                    const val = parseInt(data);
                    return isNaN(val) ? data : val.toLocaleString();
                }
            },
            {
                className: "text-end", targets: [4],
                render: function (data) {
                    const val = parseFloat(data);
                    return isNaN(val) ? data : val.toFixed(2).toLocaleString();
                }
            }
        ],
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Exportar a Excel',
                className: 'btn btn-success',
                title: function () {
                    // 🏷 Título del archivo
                    let tienda = $('#filtroTienda option:selected').text() || 'Todas las Tiendas';
                    let fechaI = $('#filtroFechaInicio').val() || '-';
                    let fechaF = $('#filtroFechaFin').val() || '-';
                    return `Reporte de Ventas - ${tienda} (${fechaI} a ${fechaF})`;
                },
                customize: function (xlsx) {
                    // ⚙️ Personalizar contenido Excel (opcional)
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'Exportar a PDF',
                className: 'btn btn-danger',
                orientation: 'landscape',
                pageSize: 'A4',
                title: '', // quitamos título por defecto
                customize: function (doc) {
                    let empresa = 'COMPUTIC AN&BET';
                    let tienda = $('#filtroTienda option:selected').text() || 'Todas las Tiendas';
                    let cliente = $('#filtroCliente option:selected').text() || 'Todos los Clientes';
                    let fechaI = $('#filtroFechaInicio').val() || '-';
                    let fechaF = $('#filtroFechaFin').val() || '-';

                    doc.content.splice(0, 0, {
                        text: empresa + '\nREPORTE DE VENTAS\n' +
                            'Cliente: ' + cliente + '\n' +
                            'Tienda: ' + tienda + '\n' +
                            'Desde: ' + fechaI + '  Hasta: ' + fechaF + '\n\n',
                        margin: [0, 0, 0, 12],
                        alignment: 'center',
                        fontSize: 12,
                        bold: true
                    });
                }
            }
        ],
        responsive: true,
        destroy: true,
        pageLength: numPaginado,
        order: [[1, orderBy]]
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