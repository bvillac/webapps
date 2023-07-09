(function () {
	"use strict";
	var treeviewMenu = $('.app-menu');
    var treeviewMenu2 = $('.app-menu2');

	// Toggle Sidebar
	$('[data-toggle="sidebar"]').click(function(event) {
		event.preventDefault();
		$('.app').toggleClass('sidenav-toggled');
	});

	// Activate sidebar treeview toggle
	$("[data-toggle='treeview']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

    // Activate sidebar treeview2 toggle
	$("[data-toggle='treeview2']").click(function(event) {
		event.preventDefault();
		if(!$(this).parent().hasClass('is-expanded')) {
			treeviewMenu2.find("[data-toggle='treeview2']").parent().removeClass('is-expanded');
		}
		$(this).parent().toggleClass('is-expanded');
	});

	// Set initial active toggle
	$("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');

    // Set initial active toggle
	//$("[data-toggle='treeview2.'].is-expanded").parent().toggleClass('is-expanded');

	//Activate bootstrip tooltips
	$("[data-toggle='tooltip']").tooltip();

})();

/*
 * Valida la Entrada del Enter
 */
function isEnter(e){
    //retornar verdadereo si presiona Enter
    var key;
    if(window.event) // IE
    {
        key = e.keyCode;
        if (key == 13 || key == 9 ){
            return true;
        }
    }else if(e.which){ // Netscape/Firefox/Opera
        key = e.which;
        // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
        //var key = nav4 ? evt.which : evt.keyCode;	
        if (key == 13 || key == 9 ){
            return true;
        }
    }
    return false;
}

function TextMayus(e) {
    e.value = e.value.toUpperCase();
}

//Convierte su primer carácter en su equivalente mayúscula.
function MyPrimera(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//Agrega 0 a la Izq Numeros
function addCeros(tam, num) {
    if (num.toString().length <= tam)
        return addCeros(tam, "0" + num)
    else
        return num;
}

function redondea(sVal, nDec) {
    var sepDecimal = ".";
    var n = parseFloat(sVal);
    var s = "0.00";
    if (!isNaN(n)) {
        n = Math.round(n * Math.pow(10, nDec)) / Math.pow(10, nDec);
        s = String(n);
        //s += (s.indexOf(".") == -1? ".": "") + String(Math.pow(10, nDec)).substr(1);
        s += (s.indexOf(sepDecimal) == -1 ? sepDecimal : "") + String(Math.pow(10, nDec)).substr(1);
        //s = s.substr(0, s.indexOf(".") + nDec + 1);
        s = s.substr(0, s.indexOf(sepDecimal) + nDec + 1);
    }
    return s;
}

function number_format(number,decimals,dec_point,thousands_sep) {
    number  = number*1;//makes sure `number` is numeric value
    var str = number.toFixed(decimals?decimals:0).toString().split('.');
    var parts = [];
    for ( var i=str[0].length; i>0; i-=3 ) {
        parts.unshift(str[0].substring(Math.max(0,i-3),i));
    }
    str[0] = parts.join(thousands_sep?thousands_sep:',');
    return str.join(dec_point?dec_point:'.');
}

function calculoCostos(costo,margen,numDecimal){
    //Aplica para los decuentos sin tener perdidas
    precio = (costo/((100-margen)/100));
    return  number_format(precio,numDecimal,SPD,SPM);
}

