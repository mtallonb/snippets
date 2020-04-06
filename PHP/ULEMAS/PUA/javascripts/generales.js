// Clases generales de interfaz
// Cambiar el tamanio de la fuente
// y dejarla al tamanio por defecto

$(document).ready(function(){
	// IE Hack
	var defaultFontSize;
	var currentFontSize;
	
	window.defaultFontSize = 1;
	window.currentFontSize = 1;

	// Funciones para mostrar y ocultar el teclado.
	// ademas de asociarlo al input correspondiente
	var kbd;
	var kbd_id;
    var kbd_show;
    window.kbd_show = false;
	var kbd_layout;
    window.kbd;
	window.kbd_id;
	
$('input[type=text]').click(function() {
	putKbd($(this).attr('id'));
});

$(':input').click(function() {
	putKbd($(this).attr('id'));
});

$('textarea').click(function() {
	putKbd($(this).attr('id'));
});


$('.teclado_selector').click(function(e){
	e.preventDefault();
	if (!window.kbd_show) {
	    window.kbd.Show(true);
        window.kbd_show = true;
    }
    else {
        window.kbd.Show(false);
        window.kbd_show = false;
    }
});
});

function keyb_callback(ch) {
    // let's bind the vkeyboard to the id
    var text = document.getElementById(window.kbd_id);
    val = text.value;

    switch(ch)
    {
        case "BackSpace":
            var min = (val.charCodeAt(val.length -1) == 10) ? 2 : 1;
            text.value = val.substr(0, val.length - min);
            break;

        case "Enter":
            text.value += "\n";
            break;
        
        default:
            text.value += ch;
    }
    
}


function putKbd(id, layout) {
	if (id != window.kbd_id)
	{   

        window.kbd_id = id;
		if (!window.kbd) {
            window.kbd = new VKeyboard('kb_container', // container id 
                                        keyb_callback,  // callback function
                                        false, // arrow keys
                                        false, // up and down keys
                                        false, // reserved
                                        false, // numpad,
                                        "Arial Unicode", // font
                                        "14px", // font size in px
                                        "#000", // font color
                                        "#F00", // font color dead keys
                                        "#E7F7D3", // keyboard background
                                        "#FFF", // keys background color
                                        "#DDD", // backgroun color selected item
                                        "#E7F7D3", // border color
                                        "#FFF", // border font color inactive key
                                        "#FFF", // background color inactive key
                                        "#F77", // border color language selector
                                        true, // show key flash on click
                                        "#14560B", // font color during flash
                                        "#E7F7D3", // key background flash
                                        "#14560B", // key border flash
                                        false, // embed keyboard into page
                                        true, // use 1 pixel gap bet keys
                                        0); // index of the initial layout
        }

        // KBD Style
        ckbd = $("#kb_container:first-child");
        ckbd.css("position","absolute");
        ckbd.css("top", $('#'+id).position().top+$('#'+id).height()+20); 
        ckbd.css("left", $('#'+id).position().left);
        //console.debug(ckbd.children().children());
        ckbd.children().css("border","2px solid rgb(14,65,7)");
        ckbd.children().css("border-bottom", "3px solid rgb(14,65,7)");

        // Dont show the keyboard
        kbd.Show(window.kbd_show);
  }
}

function setFontSize(fontSize){
    var stObj = $('#contenedor').css('font-size', fontSize+'em');
}

function changeFontSize(sizeDifference){
    window.currentFontSize = parseFloat(window.currentFontSize) + parseFloat(sizeDifference * 0.5);
    setFontSize(window.currentFontSize);
}

function revertStyles(){
    setFontSize(window.defaultFontSize);
}


