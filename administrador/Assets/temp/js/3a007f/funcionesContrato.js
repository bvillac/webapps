let tableContrato;
let tablePersonaBuscar;

function codigoExiste(value, property, lista) {
    if (lista) {
        var array = JSON.parse(lista);
        for (var i = 0; i < array.length; i++) {
            if (array[i][property] == value) {
                return false;
            }
        }
    }
    return true;
}

function retornarIndexArray(array, property, value) {
    var index = -1;
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            index = i;
            return index;
        }
    }
    return index;
}

function findAndRemove(array, property, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][property] == value) {
            array.splice(i, 1);
        }
    }
    return array;
}

//Recargar el sistio
window.addEventListener('load', function () {
    recargarGridDetalle();
}, false);

function eliminarStores(){
    sessionStorage.removeItem('cabeceraContrato');
    sessionStorage.removeItem('dts_detalleData');
}


$(document).ready(function () {
    $('#cmb_profesion').selectpicker('render');
    //Nueva Orden   
    $("#btn_nuevo").click(function () {
        //eliminarStores();
        window.location = base_url + '/Contrato/nuevo';//Retorna al Portal Principal
    });

    $("#cmd_retornar").click(function () {
        eliminarStores();
        window.location = base_url + '/Contrato';//Retorna al Portal Principal
    });

    

    

    //https://api.jqueryui.com/datepicker/
    $('.date-picker').datepicker({
        autoSize: true,
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: monthNames,
        //changeMonth: true,
        //changeYear: true,
        showButtonPanel: true,
        dateFormat: "yy-mm-dd",
        showDays: false,
        onClose: function (dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay));
        }
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
                            rowResult.Edad = objeto.Edad;
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
            let idsMes=$('#cmb_PaqueteEstudios').val();           
            let arrayDeCadenas = idsMes.split("-");
            $('#txt_numero_meses').val(arrayDeCadenas[1]);
        } else {
            //$('#cmb_punto option').remove();
            //$('#cmb_punto').selectpicker('refresh')
            $('#txt_numero_meses').val("0");
            $('#txt_numero_horas').val("0");
            swal("Error", "Selecione Paquete Aprendisaje" , "error");
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
    console.log(codigo);
    let link = base_url + '/Persona/consultarPersonaId';
    $.ajax({
        type: 'POST',
        url: link,
        data: {
            "codigo": codigo,
        },
        success: function (data) {
            if (data.status) {//Iva
                console.log(data.data['Cedula'])

                $('#txt_razon_social').val(data.data['Codigo']);
                //$('#txtDetalleItem').val(data.data['Nombre']);

                //$('#txtCantidadItem').removeAttr("disabled");
                //$('#txtPrecioItem').removeAttr("disabled");

            } else {
                //limpiarProducto();
                //$('#txtPrecioItem').attr("disabled","disabled");
                //$('#txtCantidadItem').attr("disabled","disabled");
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
    $('#cmb_CentroAtencion').val("0");
    $('#cmb_PaqueteEstudios').val("0");
    $('#cmb_ModalidadEstudios').val("0");
    $('#cmb_Idioma').val("0");
    $('#chk_tipoBeneficiario').prop("checked", false);
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
    let idsPaq=$('#cmb_PaqueteEstudios').val();
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
        rowGrid.tipoBeneficiario = "Titular";
    } else {
        rowGrid.tipoBeneficiarioID = 0;
        rowGrid.tipoBeneficiario = "Beneficiario";
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

/**************** GUARDAR DATOS CONTRATO  ******************/
function guardarContrato() {
    //let accion=($('#cmd_guardar').html()=="Guardar")?'Create':'edit';
    let accion='Create';
    var vSaldoTotal = parseFloat($('#txt_SaldoTotal').val());
    if ($('#txt_cedula').val() != "" && vSaldoTotal > 0) {      
            //$("#cmd_guardar").attr('disabled', true);
            //var ID = (accion == "edit") ? $('#txth_PedID').val() : 0;
            let link = base_url + '/Contrato/ingresarContrato';
            $.ajax({
                type: 'POST',
                url: link,
                data: {
                    "cabecera": listaCabecera(),
                    "dts_detalle": listaDetalle(),
                    "accion": accion
                },
                success: function (data) {
                    console.log("resp "+ data.status);
                    if (data.status) {
                        //sessionStorage.removeItem('cabeceraContrato');
                        //sessionStorage.removeItem('dts_detalleData');
                        swal("Contrato", data.msg ,"success");
                        window.location = base_url+'/Contrato'; 
                        
                    } else {
                        swal("Error", data.msg , "error");
                    }
                },
                dataType: "json"
            });
   

    } else {
        swal("Atención!", "No Existen datos para Guardar" , "error");
    }
}


function listaCabecera(){
    var cabecera=new Object();
    cabecera.cliIds=$('#txth_ids').val();   
    cabecera.codigoPersona=$('#txt_CodigoPersona').val();    
    cabecera.fecha_inicio=$('#dtp_fecha_inicio').val();
    cabecera.numero_recibo=$('#txt_numero_recibo').val();
    cabecera.numero_deposito=$('#txt_numero_deposito').val();
    cabecera.idsFPago=$('#txth_idsFPago').val();
    cabecera.valor=$('#txt_valor').val();
    cabecera.cuotaInicial=$('#txt_CuotaInicial').val();
    cabecera.numeroCuota=$('#txt_NumeroCuota').val();
    cabecera.valorMensual=$('#txt_ValorMensual').val(); 
    cabecera.estado='1';
    sessionStorage.cabeceraContrato = JSON.stringify(cabecera);
    //return JSON.stringify(JSON.stringify(cabecera));
    return cabecera;
}

function listaDetalle() {
    var arrayList = new Array;
    var c=0;
    if (sessionStorage.dts_detalleData) {
        var Grid = JSON.parse(sessionStorage.dts_detalleData);
        if (Grid.length > 0) {
            for (var i = 0; i < Grid.length; i++) {                
                if(parseFloat(Grid[i]['PerIdBenef'])>0){
                    let rowGrid = new Object();
                    rowGrid.PerIdBenef = Grid[i]['PerIdBenef'];
                    rowGrid.CodigoBeneficiario = Grid[i]['CodigoBeneficiario'];
                    rowGrid.TBenfId = Grid[i]['tipoBeneficiarioID'];
                    rowGrid.CentroAtencionID = Grid[i]['CentroAtencionID'];

                    

                    rowGrid.PaqueteEstudiosID = Grid[i]['PaqueteEstudiosID'];
                    rowGrid.NMeses = Grid[i]['numero_meses'];
                    rowGrid.NHoras = Grid[i]['numero_horas'];
                    rowGrid.ModalidadEstudiosID = Grid[i]['ModalidadEstudiosID'];
                    rowGrid.IdiomaID = Grid[i]['IdiomaID'];
                    rowGrid.Observaciones = "";
                    rowGrid.ExaInternacional = 0;
                    arrayList[c] = rowGrid;
                    c += 1;
                }
            }    
        }
    }
    //return JSON.stringify(arrayList);
    return arrayList;
}


