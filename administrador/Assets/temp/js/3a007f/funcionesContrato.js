let tableContrato;
let tablePersonaBuscar;

window.addEventListener('load', function () {


}, false);


$(document).ready(function () {
    $('#cmb_profesion').selectpicker('render');
    //Nueva Orden
    $("#btn_nuevo").click(function () {
        //eliminarStores();
        window.location = base_url + '/Contrato/nuevo';//Retorna al Portal Principal
    });

    $("#cmd_retornar").click(function () {
        //eliminarStores();
        window.location = base_url + '/Instructor/instructor';//Retorna al Portal Principal
    });

    //Buscar Persona
    /*$("#txtCodigoPersona").keyup(function (e) {
        e.preventDefault();
        let codigo = $(this).val();
        if (codigo.length >= 4 && codigo != "") {
            buscarPersonaDni(codigo);
        }

    });*/

    $("#txt_dni").blur(function () {
        /*let valor = document.querySelector('#txt_dni').value;
        if(!validarDocumento(valor)){
            swal("Error", "Error de DNI" , "error");
        }*/
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
                        console.log(data.data);
                        for (var i = 0; i < result.length; i++) {
                            var objeto = result[i];
                            var rowResult = new Object();
                            rowResult.label = objeto.NombreTitular + " - " + objeto.RazonSocial;
                            rowResult.value = objeto.CedulaRuc;
                            rowResult.id = objeto.Ids;
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
                            rowResult.value = objeto.Cedula ;
                            
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
                        //limpiarAutocompletarBenficiario();
                        swal("Atención!", data.msg, "info");

                    }
                }
            });
        },
        minLength: minLengthGeneral,
        select: function (event, ui) {
            $('#txt_NombreBeneficirio').val(ui.item.Nombres);
            //$('#txth_per_id').val(ui.item.id);
            $('#txt_EdadBeneficirio').val(ui.item.Edad);
            $('#txt_TelefonoBeneficirio').val(ui.item.Telefono);
            //console.log(ui);
            //console.log(ui);
        }
    });

    

});

function limpiarAutocompletar() {
    $('#txt_CodigoPersona').val("");
    //$('#txth_ids').val("");
    //$('#txth_per_id').val("");
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
    //$('#lbl_Ruc').text("");

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
                //$sql = "SELECT a.per_id Ids,a.per_cedula Cedula,a.per_nombre Nombre, ";
                //$sql .= "   a.per_apellido Apellido,a.per_fecha_nacimiento FechaNacimiento, a.per_telefono Telefono, a.per_direccion Direccion,  a.per_genero Genero, a.estado_logico Estado,date(a.fecha_creacion) FechaIng ";
                //$sql .= "   FROM " . $this->db_name . ".persona a  ";
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


