let tableContrato;
let tablePersonaBuscar;




$(document).ready(function () {
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
    $("#txtCodigoPersona").keyup(function (e) {
        e.preventDefault();
        let codigo = $(this).val();
        if (codigo.length >= 4 && codigo != "") {
            buscarPersonaDni(codigo);
        }

    });

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
                    var c=0;
                    if(data.status){
                        var result = data.data;                                                
                        for (var i = 0; i < result.length; i++) {
                            var objeto = result[i];
                            var rowResult = new Object();
                            rowResult.label=objeto.Nombre + " " + objeto.Apellido;
                            rowResult.value=objeto.Nombre + " " + objeto.Apellido;
                            rowResult.id=objeto.Ids;
                            rowResult.cedula=objeto.Cedula;
                            arrayList[c] = rowResult;
                            c += 1;
                        }
                        //console.log(arrayList);
                        response(arrayList);
                    }else{
                        //response(data.msg);
                        limpiarAutocompletar();
                        swal("Atención!", data.msg, "info");

                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            $('#txt_cedula').val(ui.item.cedula);
            $('#txt_nombres').val(ui.item.value);
            //console.log(ui);
            //console.log("Selected: " + ui.item.value + " aka " + ui.item.id);
            //console.log("Selected: " + ui.item.value + " aka " + ui.item.cedula);
        }
    });


});

function limpiarAutocompletar() {
    $('#txt_CodigoPersona').val("");
    //$('#txth_ids').val("");
    //$('#txth_per_id').val("");
    $('#txt_cedula').val("");
    $('#txt_nombres').val("");
    //$('#lbl_Ruc').text("");

}
