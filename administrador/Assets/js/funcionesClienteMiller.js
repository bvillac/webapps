var tableCliente;
document.addEventListener('DOMContentLoaded', function () {
    tableCliente = $('#tableCliente').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/ClienteMiller/getClientes",
            "dataSrc": ""
        },
        "columns": [
            //{"data":"Ids"},
            //{"data":"Tipo"},
            { "data": "Cedula" },
            { "data": "Nombre" },
            { "data": "Direccion" },
            { "data": "Correo" },
            { "data": "Telefono" },
            { "data": "Distribuidor" },
            { "data": "Precio" },
            //{"data":"Certificado"},
            { "data": "Pago" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        'dom': 'lBfrtip',
        'buttons': [
            /* {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary"
            }, */

            {
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr": "Esportar a Excel",
                "title": "REPORTE DE USUARIOS REGISTRADOS",
                "order": [[0, "asc"]],
                "className": "btn btn-success"
            },


        ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[0, "asc"]]  //Orden por defecto 1 columna
    });








});