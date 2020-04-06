// JavaScript Document

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
		document.getElementById("etiqueta").innerHTML="(Fuerza: Optima)";
		document.getElementById("barra").src="../img/optima.png";
	} else if(l<=50 && l>25) {
		document.getElementById("etiqueta").innerHTML="(Fuerza: Media)";
		document.getElementById("barra").src="../img/media.png";
	} else {
		document.getElementById("etiqueta").innerHTML="(Fuerza: Baja)";
		document.getElementById("barra").src="../img/baja.png";
	}
}
function generarPassword() {
    var pwchars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ.,:";
    var passwordlength = 16; 
    var passwd = document.getElementById('passwordG');
    passwd.value = '';

    for ( i = 0; i < passwordlength; i++ ) {
        passwd.value += pwchars.charAt( Math.floor( Math.random() * pwchars.length ) )
    }
    document.getElementById("passwordG").value=passwd.value;
}

function copiarPassword() {
    document.getElementById('passwordN').value = document.getElementById('passwordG').value;
    document.getElementById('passwordN2').value = document.getElementById('passwordG').value;
}

function cambiarEstado() {
	if(document.getElementById("estado").value=="activo") {
		document.images.imgEstado.src="../img/activo.png";
	} else {
		document.images.imgEstado.src="../img/inactivo.png";
	}
}