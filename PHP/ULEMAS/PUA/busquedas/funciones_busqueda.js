// JavaScript Document

//<![CDATA[
//*********************
// Generic stuff
//    - could live in a "generic.js" file but everybody already includes banner.js
//    - still a ways to go (NS6/DOM for ex.) but it's a start
//    - specific MENU STUFF at bottom of file
//*********************

// Hmmm - client side v srvr/hgen vars - the latter probably more efficient....
BR_DOM  = (document.getElementById) ? true : false;
BR_NS4  = (document.layers) ? true : false;
BR_IE   = (document.all) ? true : false;
BR_IE4  =  BR_IE && !BR_DOM;
BR_Mac  = (navigator.appVersion.indexOf("Mac") != -1);
BR_IE4M =  BR_IE4 && BR_Mac;
BR_NS6  =  BR_DOM && !BR_IE;

// Following vars are intended to make life generic betw. NS and IE.  If we need to
// break this down further we may be better off having independent .js includes for each.
var szSty  = BR_IE ? ".style"  : "";
var szHide = BR_IE ? "hidden"  : "hide";
var szShow = BR_IE ? "visible" : "show";
var szObjPref = BR_IE ? "" : "document.";

// Global vars
var nTimerID;
var nStdDelay = 10;


// -----------------------------
// NS4 DHTML resize fix
// -----------------------------
if (BR_NS4)
  {
  nCurWidth  = innerWidth;
  nCurHeight = innerHeight;
  onresize   = doReload;
  //alert("resize handler installed");
  }

function setAdvSearchFocus() {
  if(document.frmAdvancedSrch.SQ)
    {
    document.frmAdvancedSrch.SQ.focus();
    }
} 

function getNumberVisibleRows()
{
	var i=3;
	if (document.getElementById) {
		obj = document.getElementById("AddRow" + i);
		while (obj) {
			if (obj.style.display == "none")
				break;
			obj = document.getElementById("AddRow" + ++i);
		}
	}
	return i;
}

function addRow()
{
    var i = 0;
    for (i = 1; i < 7; i++) {
        var rowId = "AddRow" + i;
        var opId = "OP" + i;
        var sqId = "SQ" + i;
        var fieldId = "FO" + i;

        if (BR_DOM && document.getElementById(rowId).style.display=="none") {
            document.getElementById(rowId).style.display="";
            document.getElementById('RemoveRowLink').innerHTML = '<a href="javascript:removeRow();" id="removeRowTxt">Eliminar una fila</a>';
            break;
        }
    }
    if (i >= 6 && BR_DOM ) {
        document.getElementById('AddRowLink').innerHTML = 'A&ntilde;adir una fila';
    }
}

var defaultIndex = -1;
function findIndex(sel, val) {
    var ret = -1;

    if ( sel && val ) {
        options = sel.options;
        if ( options ) {
            for ( i = 0; i < options.length; i++ ) {
                if ( options[i].value == val ) {
                    ret = i;
                    break;
                }
            }
        }
    }
    return ret;
}

function removeRow()
{
    var i = 0;
    for (i = 6; i > 0; i--) {
        var rowId = "AddRow" + i;
        var opId = "OP" + i;
        var sqId = "SQ" + i;
        var fieldId = "FO" + i;

        
        if(BR_DOM && document.getElementById(rowId).style.display=="") {
            sel = document.getElementById(fieldId);
            if (sel && sel.options) {
                choice = sel.options[sel.selectedIndex].value;
                linkDiv = document.getElementById(fieldId+"Extra"+choice);
                if (linkDiv) {
                    linkDiv.style.display = "none";
                }

                if ( defaultIndex < 0 ) {
                    defaultIndex = findIndex(sel, "CITABS");
                    if ( defaultIndex < 0 ) {
                        defaultIndex = 0;
                    }
                }
                sel.selectedIndex = defaultIndex;
                choice = sel.options[sel.selectedIndex].value;
                linkDiv = document.getElementById(fieldId+"Extra"+choice);
                if (linkDiv) {
                    linkDiv.style.display = "inline";
                }
            }
            document.getElementById(rowId).style.display="none";
            document.getElementById(sqId).value = '';
            document.getElementById(opId).selectedIndex = 0;
            document.getElementById('AddRowLink').innerHTML = '<a href="javascript:addRow();">A&ntilde;adir una fila</a>';
            break;
        }
    }
    if (i <= 3 && BR_DOM ) {
        document.getElementById('RemoveRowLink').innerHTML = '<font color="#666666">Eliminar una fila</font>';
    }
}