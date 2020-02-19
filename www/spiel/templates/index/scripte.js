//PopUp Funktion
function PopUp(url,hoehe,breite)
{	
	fenster = window.open(url,'index_fenster',"toolbar=no,menubar=no,scrollbars=yes");
	//Vergrößere => resizeTo(breite, höhe)
	fenster.resizeTo(breite, hoehe);
	fenster.focus();
}

function PopUpClose()
{
	this.close();
}