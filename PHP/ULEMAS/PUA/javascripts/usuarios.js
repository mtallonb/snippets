function marcarTodos(container_id) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];

        if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            if ( checkbox.disabled == false ) {
                checkbox.checked = true;
                if ( typeof(marked_row[unique_id]) == 'undefined' || !marked_row[unique_id] ) {
                    rows[i].className += ' marked';
                    marked_row[unique_id] = true;
                }
            }
        }
    }

    return true;
}


function desmarcarTodos(container_id) {
    var rows = document.getElementById(container_id).getElementsByTagName('tr');
    var unique_id;
    var checkbox;

    for ( var i = 0; i < rows.length; i++ ) {

        checkbox = rows[i].getElementsByTagName( 'input' )[0];

        if ( checkbox && checkbox.type == 'checkbox' ) {
            unique_id = checkbox.name + checkbox.value;
            checkbox.checked = false;
            rows[i].className = rows[i].className.replace(' marked', '');
            marked_row[unique_id] = false;
        }
    }

    return true;
}

function passwordLevel(){
 	var p = document.getElementById("passwordN").value;
	l = 0;
	v1 = 'aeiou1234567890';
	v2 = 'AEIOUbcdfghjklmnpqrst';
	v3 = 'vxyzBCDFGHJKLMNPQRST';
	v4 = 'VXYZ$@#';
	for (i = 0; i < p.length; i++){
		if (v1.indexOf(p[i]) != -1) l += 1;
		else if (v2.indexOf(p[i]) != -1) l += 2;
		else if (v3.indexOf(p[i]) != -1) l += 3;
		else if (v4.indexOf(p[i]) != -1) l += 4;
		else l += 5;
	}
	l *= 3;
	if(l > 100)l = 100;
	if(l<=100 && l>50) {
		document.getElementById("etiqueta").value="Optima";
		document.getElementById("barra").src="../img/optima.png";
	} else if(l<=50 && l>25) {
		document.getElementById("etiqueta").value="Media";
		document.getElementById("barra").src="../img/media.png";
	} else {
		document.getElementById("etiqueta").value="Baja";
		document.getElementById("barra").src="../img/baja.png";
	}
}

function cambiarEstado() {
	if(document.getElementById("estado").value=="activo") {
		document.images.imgEstado.src="../img/activo.png";
	} else {
		document.images.imgEstado.src="../img/inactivo.png";
	}
}