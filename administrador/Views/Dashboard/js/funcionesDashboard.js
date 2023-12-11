$('.date-picker').datepicker( {
    closeText: 'Cerrar',
	prevText: '<Ant',
	nextText: 'Sig>',
	currentText: 'Hoy',
	monthNames: ['1 -', '2 -', '3 -', '4 -', '5 -', '6 -', '7 -', '8 -', '9 -', '10 -', '11 -', '12 -'],
	monthNamesShort: ['Enero','Febrero','Marzo','Abril', 'Mayo','Junio','Julio','Agosto','Septiembre', 'Octubre','Noviembre','Diciembre'],
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    showDays: false,
    onClose: function(dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
});

function fntSearchVMes(){
    let fecha = document.querySelector(".ventasMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/ventasMes';
        //divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#graficaMes").html(request.responseText);
                //divLoading.style.display = "none";
                return false;
            }
        }
    }
}

function fntSearchCMes(){
    let fecha = document.querySelector(".ventasCompraMes").value;
    if(fecha == ""){
        swal("", "Seleccione mes y año" , "error");
        return false;
    }else{
        let request = (window.XMLHttpRequest) ? 
            new XMLHttpRequest() : 
            new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Dashboard/compraMes';
        //divLoading.style.display = "flex";
        let formData = new FormData();
        formData.append('fecha',fecha);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                $("#graficaCompraMes").html(request.responseText);
                //divLoading.style.display = "none";
                return false;
            }
        }
    }
}




window.addEventListener('load', function() { 
    //let fecha = new Date();
    //f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear()  
    //let mesano=(fecha.getMonth() +1) + "-" + fecha.getFullYear() ;
    //document.querySelector(".ventasCompraMes").value=mesano;
    //document.querySelector(".ventasMes").value=mesano;
    //fntSearchVMes();
    //fntSearchCMes();
}, false);