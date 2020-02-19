//Deklarationen
var anzeige_objekt 	= document.getElementById('overDiv');		//Welches Objekt soll angezeigt werden, bei einem MouseOver?
var width 			= 150;
var x 				= 0;										//x-Koordinaten des OBjektes
var y				= 0;										//y-Koordainten des Objektes
var xoffset 		= 10;
var yoffset 		= 10;
var IE 				= false;									//Wenn der Navigator ein IE ist, dann ist dieser Wert auf True gesetzt!
var NS 				= false;									//Wenn es sich um nen NEtscpae browser handelt, dann wert = true
var page_height		= 0;										//Seiten höhe
var page_width 		= 0;										//Seiten breite
var delay			= 350;										//Verzögerung des mouseovers in milisekunden
var programmstatus =0;											//0 böse... 1 =gut

//Setze IE oder NS variable
if( navigator.appName == 'Netscape' )
{
	//Setze Navigator-Werte
	IE = false;
	NS = true;
	//setze Seitenbreite und Seitenhöhe
	page_height = this.innerHeight;
	page_width = this.innerWidth;		
}
else
{
	//Setze Navigator-Werte
	IE = true;
	NS = false;
	//setze Seitenbreite und Seitenhöhe
	page_height = document.body.clientHeight;
	page_width = document.body.clientWidth;
}

//Setze MouseEventHandler :)
document.onmousemove = mouseMove;

function showElement(title, text, color)
{
	//Setze Text zum anzeigen
	anzeige_text = "<table id=\"mouseovertable\" bgcolor=\""+color+"\" width=\"" + width + "\"><tr><th>" + title + "</th></tr><tr><td>"+text+"</td></tr></table>";
	
	//Schreibe Text in AnzeigeObjekt
	anzeige_objekt.innerHTML = anzeige_text;
	
	//Setze PRogrammstatus 
	programmstatus =1;
	
	//Objekt bewegen
	this.setTimeout("BewegeObjekt(anzeige_objekt, x+xoffset, y+yoffset)", delay);
}

function mouseMove(e) 
{
	//Ermittle Browser
	if( NS == true )	//Netscape oder Mozilla
	{
		//Setze aktuelle Mauszeigerposition
		x = e.pageX;
		y = e.pageY;
	}
	else		//z.b. Ie oder anderer Browser
	{
		//Setze aktuelle Mauszeigerposition
		x=event.clientX + document.body.scrollLeft; 	//Scrollbalken Verschiebung hinzufügen
		y=event.clientY + document.body.scrollTop;		//Scrollbalken Verschiebung hinzufügen
	}
	
	//Wenn Objekt visibility == Visible... ansonsten nicht bewegen!
	if( anzeige_objekt.style.visibility == "visible" )
	{
		//Verändere nun die Position des Anzeigeobjekts
		BewegeObjekt(anzeige_objekt, x+xoffset, y+yoffset);
	}
}
   	
/*Diese Funktion bewegt ein Objekt :)*/
function BewegeObjekt(objekt, xNeu, yNeu)
{
	//Nur ausführen, wenn Programmstatus 1 ist!
	if( programmstatus == 1 )
	{	
		//Wird MouseOver-Div ausserhalb des Anzeigebereichs (Breite) angezeigt?
		if( xNeu <= page_width-width )
		{
			objekt.style.left = xNeu;	//Setze linke koordinaten
		}
		else
		{		
			objekt.style.left = xNeu-width-50;
		}
		//objekt.style.left = xNeu;
		
		//ÜBerprüfen ob MouseOver-Div ausserhalb des Anzeigebereichs(höhe) angezeigt wird!
		if( yNeu <= page_height-200)
		{
			//Sezte untere koordinaten
			objekt.style.top = yNeu;
		}
		else
		{
			//Setze obere koordinaten
			objekt.style.top = yNeu-215;
		}
		
		//Element anzeigen
		objekt.style.visibility = "visible";
	}
}
	 	
function hideElement()
{
	anzeige_objekt.style.visibility = "hidden";	//Angezeigte Objekt wird verschoben :)
	programmstatus = 0;//Veränadere Programmstatus
}