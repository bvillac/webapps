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
            { "data": "Orden" },
            { "data": "Solicitud" },
            { "data": "Tienda" },
            { "data": "codigo" },
            { "data": "nombre" },
            { "data": "can_ped" },
            { "data": "t_venta" }
        ],
        "columnDefs": [
            { 'className': "textleft", "targets": [0, 1, 2, 3, 4] },
            { 'className': "textcenter", "targets": [5, 6] }
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
        tableReporte.ajax.reload();
    });

});