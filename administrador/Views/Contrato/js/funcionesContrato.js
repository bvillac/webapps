document.write(`<script src="${base_url}/Assets/js/cedulaRucPass.js"></script>`);
let tableContrato;
let tablePersonaBuscar;



document.addEventListener('DOMContentLoaded', function () {
    tableContrato = $('#tableContrato').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": cdnTable
        },
        "ajax": {
            "url": " " + base_url + "/Contrato/consultarContrato",
            "dataSrc": ""
        },
        "columns": [
            { "data": "Numero" },
            { "data": "FechaIni" },
            { "data": "RazonSocial" },
            { "data": "Total" },
            { "data": "CuoInicial" },
            { "data": "Saldo" },
            { "data": "Npagos" },
            { "data": "Vmensual" },
            { "data": "Estado" },
            { "data": "options" }
        ],
        "columnDefs": [
            { 'className': "textright", "targets": [0] },
            { 'className': "textcenter", "targets": [1] },//Agregamos la clase que va a tener la columna
            { 'className': "textleft", "targets": [2] },
            { 'className': "textright", "targets": [3] },
            { 'className': "textright", "targets": [4] },
            { 'className': "textright", "targets": [5] },
            { 'textcenter': "textcenter", "targets": [6] },
            { 'className': "textright", "targets": [7] }
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
              "extend": "excelHtml5",
              "text": "<i class='fa fa-file-excel'></i> Excel",
              "titleAttr": "Esportar a Excel",
              "title": "REPORTE GENERAL CONTRATO",
              "order": [[0, "asc"]],
              "className": "btn btn-success"
            },
            {
              "extend": "pdfHtml5",
              "text": "<i class='fa fa-file-pdf'></i> PDF",
              "titleAttr": "Esportar a PDF",
              "pageSize": "LETTER",
              "title": "REPORTE GENERAL CONTRATO",
              "order": [[0, "asc"]],
              "className": "btn btn-secondary"
            }
          ],
        "resonsieve": "true",
        "bDestroy": true,
        "iDisplayLength": 10,//Numero Items Retornados
        "order": [[1, "desc"]]  //Orden por defecto 1 columna
    });

});

//Recargar el sistio
window.addEventListener('load', function () {
    recargarGridDetalle();
}, false);

function eliminarStores() {
    sessionStorage.removeItem('cabeceraContrato');
    sessionStorage.removeItem('dts_detalleData');
}


$(document).ready(function () {
    //$('#cmb_profesion').selectpicker('render');
    //Nueva Orden   
    $("#btn_nuevo").click(function () {
        //eliminarStores();
        window.location = base_url + '/Contrato/nuevo';//Retorna al Portal Principal
    });

    $("#cmd_retornar").click(function () {
        eliminarStores();
        window.location = base_url + '/Contrato';//Retorna al Portal Principal
    });

    $("#txt_per_cedula").blur(function () {
        let valor = document.querySelector('#txt_per_cedula').value;
        /*if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }*/
    });






    $("#txt_CodigoPersona").autocomplete({
        source: function (request, response) {
            let link = base_url + '/ClienteMiller/buscarAutoCliente';
            $.ajax({
                type: 'POST',
                url: link,
                dataType: "json",
                data: {
                    buscar: request.term
                },
                success: function (data) {
                    var arrayList = new Array;
                    var c = 0;
                    if (data.status) {
                        var result = data.data;
                        //console.log(data.data);
                        for (var i = 0; i < result.length; i++) {
                            var objeto = result[i];
                            var rowResult = new Object();
                            rowResult.label = objeto.NombreTitular + " - " + objeto.RazonSocial;
                            rowResult.value = objeto.CedulaRuc;
                            rowResult.id = objeto.Ids;
                            rowResult.Per_id = objeto.per_id;
                            rowResult.fpg_id = objeto.FpagIds;
                            rowResult.FpagoNombre = objeto.FpagoNombre;
                            rowResult.OcupaNombre = objeto.OcupaNombre;
                            rowResult.CedulaRuc = objeto.CedulaRuc;
                            rowResult.RazonSocial = objeto.RazonSocial;
                            rowResult.DireccionCliente = objeto.DireccionCliente;
                            rowResult.TelefCliente = objeto.TelefCliente;
                            rowResult.TelfOficina = objeto.TelfOficina;
                            rowResult.Cargo = objeto.Cargo;
                            rowResult.Antiguedad = objeto.Antiguedad;
                            rowResult.IngMensual = objeto.IngMensual;
                            rowResult.NombreTitular = objeto.NombreTitular;
                            rowResult.DireccionDomicilio = objeto.DireccionDomicilio;
                            rowResult.TelfCelular = objeto.TelfCelular;
                            rowResult.RefBanco = objeto.RefBanco;
                            arrayList[c] = rowResult;
                            c += 1;
                        }
                        //console.log(arrayList);
                        response(arrayList);
                    } else {
                        //response(data.msg);
                        limpiarAutocompletar();
                        swal("Atención!", data.msg, "info");

                    }
                }
            });
        },
        minLength: minLengthGeneral,
        select: function (event, ui) {
            $('#txth_ids').val(ui.item.id);
            $('#txth_per_id').val(ui.item.Per_id);
            $('#txth_idsFPago').val(ui.item.fpg_id);
            $('#txt_cedula').val(ui.item.CedulaRuc);
            $('#txt_nombres').val(ui.item.NombreTitular);
            $('#txt_razon_social').val(ui.item.RazonSocial);
            $('#txt_cargo').val(ui.item.Cargo);
            $('#txt_ingreso_mensual').val(ui.item.IngMensual);
            $('#txt_antiguedad').val(ui.item.Antiguedad);
            $('#txt_dir_domicilio').val(ui.item.DireccionDomicilio);
            $('#txt_tel_domicilio').val(ui.item.TelfCelular);
            $('#txt_dir_trabajo').val(ui.item.DireccionCliente);
            $('#txt_tel_trabajo').val(ui.item.TelfOficina);
            $('#txt_referencia').val(ui.item.RefBanco);
            $('#txt_forma_pago').val(ui.item.FpagoNombre);
            $('#txt_ocupacion').val(ui.item.OcupaNombre);

        }
    });

    $("#txt_CodigoBeneficiario").autocomplete({
        source: function (request, response) {
            let link = base_url + '/Persona/buscarAutoPersona';
            $.ajax({
                type: 'POST',
                url: link,
                dataType: "json",
                data: {
                    buscar: request.term
                },
                success: function (data) {
                    var arrayList = new Array;
                    var c = 0;
                    if (data.status) {
                        var result = data.data;
                        for (var i = 0; i < result.length; i++) {
                            var objeto = result[i];
                            var rowResult = new Object();
                            rowResult.label = objeto.Cedula + " " + objeto.Nombre + " " + objeto.Apellido;
                            rowResult.value = objeto.Cedula;

                            rowResult.id = objeto.Ids;
                            rowResult.Cedula = objeto.Cedula;
                            rowResult.Nombres = objeto.Nombre + " " + objeto.Apellido;;
                            rowResult.FechaNacimiento = objeto.FechaNacimiento;
                            rowResult.Telefono = objeto.Telefono;
                            
                            //rowResult.Edad = objeto.Edad;
                            rowResult.Edad = calcularEdad(objeto.FechaNacimiento);
                            arrayList[c] = rowResult;
                            c += 1;
                        }
                        response(arrayList);
                    } else {
                        //response(data.msg);
                        limpiarTexbox();
                        swal("Atención!", data.msg, "info");

                    }
                }
            });
        },
        minLength: minLengthGeneral,
        select: function (event, ui) {
            $('#txt_NombreBeneficirio').val(ui.item.Nombres);
            $('#txt_EdadBeneficirio').val(ui.item.Edad);
            $('#txt_TelefonoBeneficirio').val(ui.item.Telefono);
            $('#txth_per_idBenef').val(ui.item.id);

        }
    });

    $("#txt_CuotaInicial").keyup(function (e) {
        e.preventDefault();
        let CuotaInicial = parseFloat($(this).val());
        let saldo = 0;
        if (CuotaInicial > 0 && CuotaInicial != "") {
            let valor = $('#txt_valor').val();
            saldo = calcularSaldo(valor, CuotaInicial);
            $('#txt_SaldoTotal').val(redondea(saldo, N2decimal))
        } else {
            $('#txt_SaldoTotal').val(redondea(saldo, N2decimal))
        }
    });

    $("#txt_valor").keyup(function (e) {
        e.preventDefault();
        let valor = parseFloat($(this).val());
        let saldo = 0;
        if (valor > 0 && valor != "") {
            let CuotaInicial = parseFloat($('#txt_CuotaInicial').val());
            saldo = calcularSaldo(valor, CuotaInicial);
            $('#txt_SaldoTotal').val(redondea(saldo, N2decimal))
        } else {
            $('#txt_SaldoTotal').val(redondea(saldo, N2decimal))
        }
    });

    $("#txt_CuotaInicial").blur(function (e) {
        e.preventDefault();
        let CuotaInicial = parseFloat($(this).val());
        let valor = parseFloat($('#txt_valor').val());
        document.querySelector('#txt_CuotaInicial').value = redondea(CuotaInicial, N2decimal);
        if (CuotaInicial > valor) {
            document.querySelector('#txt_CuotaInicial').value = "0.00";
            document.querySelector('#txt_SaldoTotal').value = "0.00";
            swal("Atención!", "La cuota inicial es mayor que el valor de pago", "info");
        }
        recalculaTotal();
    });

    $("#txt_valor").blur(function (e) {
        e.preventDefault();
        let valor = parseFloat($(this).val());
        document.querySelector('#txt_valor').value = redondea(valor, N2decimal);
        recalculaTotal();
    });


    $("#txt_NumeroCuota").keyup(function (e) {
        e.preventDefault();
        let nCuota = parseFloat($(this).val());
        let vmeses = 0;
        if (nCuota > 0 && nCuota != "") {
            let saldoTotal = parseFloat($('#txt_SaldoTotal').val());
            vmeses = calcularMeses(saldoTotal, nCuota);
            $('#txt_ValorMensual').val(redondea(vmeses, N2decimal))
        } else {
            $('#txt_ValorMensual').val(redondea(vmeses, N2decimal))
        }
    });
    $("#txt_NumeroCuota").blur(function (e) {
        e.preventDefault();
        recalculaTotal();
    });



    $('#cmb_PaqueteEstudios').change(function () {
        if ($('#cmb_PaqueteEstudios').val() != 0) {
            let idsMes = $('#cmb_PaqueteEstudios').val();
            let arrayDeCadenas = idsMes.split("-");
            //$('#txt_numero_meses').val(arrayDeCadenas[1]);
            $('#txt_numero_meses').val("0");
        } else {
            //$('#cmb_punto option').remove();
            //$('#cmb_punto').selectpicker('refresh')
            $('#txt_numero_meses').val("0");
            $('#txt_numero_horas').val("0");
            swal("Error", "Selecione Paquete Aprendisaje", "error");
        }
    });

});

function calcularMeses(saldoTotal, nCuota) {
    let valorMes = saldoTotal / nCuota;
    return valorMes;
}

function calcularSaldo(valor, CuotaInicial) {
    return valor - CuotaInicial;
}

function recalculaTotal() {
    let nValor = parseFloat($('#txt_valor').val());
    let nCuotaInicial = parseFloat($('#txt_CuotaInicial').val());
    let nCuota = $('#txt_NumeroCuota').val();
    let nSaldo = nValor - nCuotaInicial;
    let vMeses = 0;
    if (nCuota > 0) {
        vMeses = nSaldo / nCuota;
    }

    $('#txt_SaldoTotal').val(redondea(nSaldo, N2decimal));
    $('#txt_ValorMensual').val(redondea(vMeses, N2decimal));
}




function limpiarAutocompletar() {
    $('#txt_CodigoPersona').val("");
    $('#txth_ids').val("");
    $('#txth_per_id').val("");
    $('#txt_cedula').val("");
    $('#txt_nombres').val("");
    $('#txt_razon_social').val("");
    $('#txt_cargo').val("");
    $('#txt_ingreso_mensual').val("");
    $('#txt_antiguedad').val("");
    $('#txt_dir_domicilio').val("");
    $('#txt_tel_domicilio').val("");
    $('#txt_dir_trabajo').val("");
    $('#txt_tel_trabajo').val("");
    $('#txt_referencia').val("");
    $('#txt_forma_pago').val("");
    $('#txt_ocupacion').val("");
    $('#txth_idsFPago').val("");

}




function buscarPersonaId(codigo) {
    let link = base_url + '/Persona/consultarPersonaId';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "codigo": codigo,
        },
        success: function (data) {
            if (data.status) {//Iva
                //console.log(data.data['Cedula'])
                $('#txt_razon_social').val(data.data['Codigo']);

            } else {
                swal("Atención!", "No Existen Datos", "error");
            }
        },
        dataType: "json"
    });
}


/*######################### AGREGAR BENEFICIARIOS ###################################*/
function agregarItemsDoc() {
    var tGrid = 'TbG_tableBeneficiario';
    var nombre = $('#txt_CodigoBeneficiario').val();
    if ($('#txt_CodigoBeneficiario').val() != "") {
        var valor = $('#txt_CodigoBeneficiario').val();
        //*********   AGREGAR ITEMS *********
        var arr_Grid = new Array();
        if (sessionStorage.dts_detalleData) {
            /*Agrego a la Sesion*/
            arr_Grid = JSON.parse(sessionStorage.dts_detalleData);
            var size = arr_Grid.length;
            if (size > 0) {
                //Varios Items
                if (codigoExiste(nombre, 'CodigoBeneficiario', sessionStorage.dts_detalleData)) {//Verifico si el Codigo Existe  para no Dejar ingresar Repetidos
                    arr_Grid[size] = objDataRow();
                    sessionStorage.dts_detalleData = JSON.stringify(arr_Grid);
                    addVariosItem(tGrid, arr_Grid, -1);
                    limpiarTexbox();
                } else {
                    swal("Atención!", "Item ya existe en su lista", "error");
                }
            } else {
                /*Agrego a la Sesion*/
                //Primer Items
                arr_Grid[0] = objDataRow();
                sessionStorage.dts_detalleData = JSON.stringify(arr_Grid);
                addPrimerItem(tGrid, arr_Grid, 0);
                limpiarTexbox();
            }
        } else {
            //No existe la Session
            //Primer Items
            arr_Grid[0] = objDataRow();
            sessionStorage.dts_detalleData = JSON.stringify(arr_Grid);
            addPrimerItem(tGrid, arr_Grid, 0);
            limpiarTexbox();
        }
    } else {
        swal("Atención!", "No Existen Información", "error");
    }
}


function limpiarTexbox() {
    $('#txth_per_idBenef').val("");
    $('#txt_CodigoBeneficiario').val("");
    $('#txt_NombreBeneficirio').val("");
    $('#txt_EdadBeneficirio').val("0");
    $('#txt_TelefonoBeneficirio').val("0");
    $('#txt_numero_meses').val("0");
    $('#txt_numero_horas').val("0");
    $('#cmb_CentroAtencion').val("0");
    $('#cmb_PaqueteEstudios').val("0");
    $('#cmb_ModalidadEstudios').val("0");
    $('#cmb_Idioma').val("0");
    $('#chk_tipoBeneficiario').prop("checked", false);
    $('#chk_ExamenInter').prop("checked", false);
}

function objDataRow() {
    rowGrid = new Object();
    rowGrid.PerIdBenef = $('#txth_per_idBenef').val();
    rowGrid.CodigoBeneficiario = $('#txt_CodigoBeneficiario').val();
    rowGrid.NombreBeneficirio = $('#txt_NombreBeneficirio').val();
    rowGrid.EdadBeneficirio = $('#txt_EdadBeneficirio').val();
    rowGrid.TelefonoBeneficirio = $('#txt_TelefonoBeneficirio').val();
    rowGrid.CentroAtencionID = $('#cmb_CentroAtencion').val();
    rowGrid.CentroAtencion = $('select[id="cmb_CentroAtencion"] option:selected').text();
    //Separa el String Codigo y Meses
    let idsPaq = $('#cmb_PaqueteEstudios').val();
    let arrayPaquet = idsPaq.split("-");
    rowGrid.PaqueteEstudiosID = arrayPaquet[0];
    rowGrid.PaqueteEstudios = $('select[id="cmb_PaqueteEstudios"] option:selected').text();

    rowGrid.ModalidadEstudiosID = $('#cmb_ModalidadEstudios').val();
    rowGrid.ModalidadEstudios = $('select[id="cmb_ModalidadEstudios"] option:selected').text();
    rowGrid.IdiomaID = $('#cmb_Idioma').val();
    rowGrid.Idioma = $('select[id="cmb_Idioma"] option:selected').text();
    rowGrid.numero_meses = $('#txt_numero_meses').val();
    rowGrid.numero_horas = $('#txt_numero_horas').val();
    if ($('#chk_tipoBeneficiario').prop('checked')) {
        rowGrid.tipoBeneficiarioID = 1;
        rowGrid.tipoBeneficiario = "TITULAR";
    } else {
        rowGrid.tipoBeneficiarioID = 0;
        rowGrid.tipoBeneficiario = "BENEFICIARIO";
    }
    if ($('#chk_ExamenInter').prop('checked')) {
        rowGrid.ExamenInter = 1;
    } else {
        rowGrid.ExamenInter = 0;
    }

    return rowGrid;
}


function addPrimerItem(TbGtable, lista, i) {
    /*Remuevo la Primera fila*/
    $('#' + TbGtable + ' >table >tbody').html("");
    /*Agrego a la Tabla de Detalle*/
    $('#' + TbGtable).append(retornaFilaData(i, lista, TbGtable, true));
}

function addVariosItem(TbGtable, lista, i) {
    i = (i == -1) ? ($('#' + TbGtable + ' tr').length) - 1 : i;
    $('#' + TbGtable).append(retornaFilaData(i, lista, TbGtable, true));
}

function retornaFilaData(c, Grid, TbGtable, op) {
    var strFila = "";
    strFila += '<td>' + Grid[c]['CodigoBeneficiario'] + '</td>';
    strFila += '<td>' + Grid[c]['NombreBeneficirio'] + '</td>';
    strFila += '<td>' + Grid[c]['tipoBeneficiario'] + '</td>';
    strFila += '<td>' + Grid[c]['CentroAtencion'] + '</td>';
    strFila += '<td>' + Grid[c]['PaqueteEstudios'] + '</td>';
    strFila += '<td>' + Grid[c]['numero_meses'] + '</td>';
    strFila += '<td>' + Grid[c]['numero_horas'] + '</td>';
    strFila += '<td>' + Grid[c]['ModalidadEstudios'] + '</td>';
    strFila += '<td>' + Grid[c]['Idioma'] + '</td>';
    strFila += '<td>' + Grid[c]['EdadBeneficirio'] + '</td>';
    strFila += '<td>' + Grid[c]['TelefonoBeneficirio'] + '</td>';
    strFila += '<td>';
    //strFila += ' <a href="#" class="link_delete" onclick="event.preventDefault();editarItemsDetalle(\'' + Grid[c]['CodigoBeneficiario'] + '\',\'' + TbGtable + '\');"><i class="fa fa-pencil"></i></a>';
    strFila += ' <a href="#" class="link_delete" onclick="event.preventDefault();eliminarItemsDetalle(\'' + Grid[c]['CodigoBeneficiario'] + '\',\'' + TbGtable + '\');"><i class="fa fa-trash"></i></a>';
    strFila += '</td>';
    if (op) {
        strFila = '<tr class="odd gradeX">' + strFila + '</tr>';
    }
    return strFila;
}

function recargarGridDetalle() {
    var tGrid = 'TbG_tableBeneficiario';
    if (sessionStorage.dts_detalleData) {
        var arr_Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (arr_Grid.length > 0) {
            $('#' + tGrid + ' >table >tbody').html("");
            for (var i = 0; i < arr_Grid.length; i++) {
                $('#' + tGrid).append(retornaFilaData(i, arr_Grid, tGrid, true));
            }
        }
    }
}


function eliminarItemsDetalle(codigo, TbGtable) {
    let ids = "";
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            $('#' + TbGtable + ' tr').each(function () {
                ids = $(this).find("td").eq(0).html();
                if (ids == codigo) {
                    var array = findAndRemove(Grid, 'CodigoBeneficiario', ids);
                    sessionStorage.dts_detalleData = JSON.stringify(array);
                    //if (count==0){sessionStorage.removeItem('detalleGrid')} 
                    $(this).remove();
                }
            });
        }
    }
}

/**************** GUARDAR DATOS PERSONA  ******************/
function guardarPersona() {
    //var Ids = document.querySelector('#txth_ids').value;
    var per_cedula = document.querySelector('#txt_per_cedula').value;
    var per_nombre = document.querySelector('#txt_per_nombre').value;
    var per_apellido = document.querySelector('#txt_per_apellido').value;
    var per_fecha_nacimiento = document.querySelector('#dtp_fecha_nacimiento').value;
    var per_telefono = document.querySelector('#txt_per_telefono').value;
    var per_direccion = document.querySelector('#txt_per_direccion').value;
    var per_genero = document.querySelector('#txt_per_genero').value;
    if (per_cedula == '' || per_nombre == '' || per_apellido == '' || per_fecha_nacimiento == '' || per_telefono == '' || per_direccion == '' || per_genero == '')//Validacin de Campos
    {
        swal("Atención", "Todos los campos son obligatorios.", "error");
        return false;
    }
    //Verificas los elementos conl clase valid para controlar que esten ingresados
    let elementsValid = document.getElementsByClassName("valid");
    for (let i = 0; i < elementsValid.length; i++) {
        if (elementsValid[i].classList.contains('is-invalid')) {
            swal("Atención", "Por favor verifique los campos ingresados (Color Rojo).", "error");
            return false;
        }
    }

    var dataPersona = new Object();
    //dataPersona.ids = Ids;
    dataPersona.per_cedula = per_cedula;
    dataPersona.per_nombre = per_nombre;
    dataPersona.per_apellido = per_apellido;
    dataPersona.per_fecha_nacimiento = per_fecha_nacimiento;
    dataPersona.per_telefono = per_telefono;
    dataPersona.per_direccion = per_direccion;
    dataPersona.per_genero = per_genero;
    //sessionStorage.dataPersona = JSON.stringify(dataPersona);
    let link = base_url + '/Persona/ingresarPersonaDatos';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "persona": JSON.stringify(dataPersona),
            "accion": "Create"
        },
        success: function (data) {
            if (data.status) {
                //sessionStorage.removeItem('dataPersona');
                swal("Persona Contrato", data.msg, "success");
                $('#txth_per_idBenef').val(data.numero);
                $('#txt_CodigoBeneficiario').val(per_cedula);
                $('#txt_NombreBeneficirio').val(per_nombre + " " + per_apellido);
                $('#txt_TelefonoBeneficirio').val(per_telefono);
                //$('#dtp_fecha_nacimiento').val(calcularEdad(per_fecha_nacimiento));
                $('#txt_EdadBeneficirio').val(calcularEdad(per_fecha_nacimiento));
                //alert("IDS = " + data.numero);                
                $('#modalFormPersona').modal("hide");
                limpiarTexboxPersona();
            } else {
                swal("Error", data.msg, "error");
            }
        },
        dataType: "json"
    });

}

function limpiarTexboxPersona() {
    $('#txt_per_cedula').val("");
    $('#dtp_fecha_nacimiento').val("");
    $('#txt_per_nombre').val("");
    $('#txt_per_apellido').val("");
    $('#txt_per_telefono').val("");
    $('#txt_per_direccion').val("");
    
}

/**************** GUARDAR DATOS CONTRATO  ******************/
function guardarContrato() {
    //let accion=($('#cmd_guardar').html()=="Guardar")?'Create':'edit';
    let accion = 'Create';
    var vSaldoTotal = parseFloat($('#txt_SaldoTotal').val());
    var ValorContrato = parseFloat($('#txt_valor').val());
    var vFecha = $('#dtp_fecha_inicio').val();
    if ($('#txt_cedula').val() != "" && vSaldoTotal >= 0 && ValorContrato > 0 && vFecha != "") {
        //$("#cmd_guardar").attr('disabled', true);
        //var ID = (accion == "edit") ? $('#txth_PedID').val() : 0;
        let link = base_url + '/Contrato/ingresarContrato';
        $.ajax({
            type: 'POST',
            url: link,
            data: {
                "cabecera": listaCabecera(),
                "dts_detalle": listaDetalle(),
                "dts_referencia": listaReferencia(),
                "accion": accion
            },
            success: function (data) {
                //console.log("resp " + data.status);
                if (data.status) {
                    sessionStorage.removeItem('cabeceraContrato');
                    sessionStorage.removeItem('dts_detalleData');
                    swal("Contrato", data.msg, "success");
                    window.location = base_url + '/Contrato';

                } else {
                    swal("Error", data.msg, "error");
                }
            },
            dataType: "json"
        });


    } else {
        swal("Atención!", "Ingresar datos del Cliente,Beneficiarios y Totales", "error");
    }
}

function listaCabecera() {
    var cabecera = new Object();
    cabecera.cliIds = $('#txth_ids').val();
    cabecera.codigoPersona = $('#txt_CodigoPersona').val();
    cabecera.numeroContrato = $('#txt_NumeroContrato').val();
    cabecera.fecha_inicio = $('#dtp_fecha_inicio').val();
    cabecera.numero_recibo = $('#txt_numero_recibo').val();
    cabecera.numero_deposito = $('#txt_numero_deposito').val();
    cabecera.idsFPago = $('#txth_idsFPago').val();
    cabecera.tipoPago = $('#cmb_tipo_pago').val();
    cabecera.valor = $('#txt_valor').val();
    cabecera.cuotaInicial = $('#txt_CuotaInicial').val();
    cabecera.numeroCuota = $('#txt_NumeroCuota').val();
    cabecera.valorMensual = $('#txt_ValorMensual').val();
    cabecera.observacion = $('#txta_observacion').val();
    console.log(cabecera.observacion);
    cabecera.estado = '1';
    sessionStorage.cabeceraContrato = JSON.stringify(cabecera);
    //return JSON.stringify(JSON.stringify(cabecera));
    return cabecera;
}

function listaDetalle() {
    var arrayList = new Array;
    var c = 0;
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {
                if (parseFloat(Grid[i]['PerIdBenef']) > 0) {
                    let rowGrid = new Object();
                    rowGrid.PerIdBenef = Grid[i]['PerIdBenef'];
                    rowGrid.CodigoBeneficiario = Grid[i]['CodigoBeneficiario'];
                    rowGrid.TBenfId = Grid[i]['tipoBeneficiarioID'];
                    rowGrid.CentroAtencionID = Grid[i]['CentroAtencionID'];
                    rowGrid.EdadBeneficirio = Grid[i]['EdadBeneficirio'];
                    rowGrid.PaqueteEstudiosID = Grid[i]['PaqueteEstudiosID'];
                    rowGrid.NMeses = Grid[i]['numero_meses'];
                    rowGrid.NHoras = Grid[i]['numero_horas'];
                    rowGrid.ModalidadEstudiosID = Grid[i]['ModalidadEstudiosID'];
                    rowGrid.IdiomaID = Grid[i]['IdiomaID'];
                    rowGrid.Observaciones = "";
                    rowGrid.ExaInternacional = Grid[i]['ExamenInter'];
                    arrayList[c] = rowGrid;
                    c += 1;
                }
            }
        }
    }
    //return JSON.stringify(arrayList);
    return arrayList;
}

function listaReferencia() {
    var arrayList = new Array;
    var c = 0;
    for (var i = 0; i < 3; i++) {
        if ($('#txt_refNombre' + i).val() != "") {
            let rowRef = new Object();
            rowRef.refNombre = $('#txt_refNombre' + i).val();
            rowRef.refDireccion = $('#txt_refDireccion' + i).val();
            rowRef.refTelefono = $('#txt_refTelefono' + i).val();
            rowRef.refCiudad = $('#txt_refCiudad' + i).val();
            arrayList[c] = rowRef;
            c += 1;
            sessionStorage.dts_referencia = JSON.stringify(arrayList);
        }
    }
    
    //return JSON.stringify(arrayList);
    return arrayList;
}

function openModaladdPersona() {
    //document.querySelector('#txth_ids').value = "";//IDS oculto hiden
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");//Cambiar las Clases para los colores
    document.querySelector('#titleModal').innerHTML = "Nueva Persona";
    document.querySelector("#formPersona").reset();
    $('#modalFormPersona').modal('show');
}


function fntDesactivarContrato(ids) {
    swal({
        title: "Desactivar Registro",
        text: "¿Realmente quiere Desactivar el Contrato?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Desactivar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function (isConfirm) {

        if (isConfirm) {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Contrato/desativarContrato';
            var strData = "ids=" + ids;
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Atención!", objData.msg, "success");
                        tableContrato.api().ajax.reload(function () {

                        });
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                }
            }
        }

    });

}


