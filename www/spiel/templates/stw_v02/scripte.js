//PopUp Funktion
function PopUp(url,hoehe,breite)
{	
	fenster = window.open(url,'stw_fenster',"toolbar=no,menubar=no,scrollbars=yes");
	//Vergrößere => resizeTo(breite, höhe)
	fenster.resizeTo(breite, hoehe);
	fenster.focus();
}

//PopClose funktion
function PopUpClose()
{
	this.close();
}

//Funktion öffnet ein TechTree PopUp
function openTT(code)
{
	fenster_tt = window.open('techtree.php?type='+code, 'tt_fenster', 'toolbar=no, menubar=no, scrollbars=yes');
	fenster_tt.resizeTo(950, 700);
	fenster_tt.focus();
}
	

/*funktion ändert bei der missionsauswahlä alle wichtigen daten*/
function changeMissionsdaten()
{
	//Deklarationen
    z_entfernung = 30;
    y_entfernung = 50;
    x_entfernung = 100;
    
    //SourceKoordinaten
    a1 = document.getElementsByName('xs')[0].value;
    a2 = document.getElementsByName('ys')[0].value;
    a3 = document.getElementsByName('zs')[0].value;
    
    
    //DestinatinoKoordinaten
    b1 = document.getElementsByName('x')[0].value;
    b2 = document.getElementsByName('y')[0].value;
	b3 = document.getElementsByName('z')[0].value;

	//Entfernungsberechnung
	entfernung1 = Math.round(
					Math.sqrt(
						(Math.pow((a1 - b1)*x_entfernung,2)) + 
    					(Math.pow((a2 - b2)*y_entfernung,2)) + 
    					(Math.pow((a3 - b3)*z_entfernung,2))));
    	
    //Dauer setzen!
    max_ges 	= document.getElementById('max_geschwindigkeit').value * (document.getElementsByName('geschwindigkeit')[0].value);
    ges_prozent = document.getElementsByName('geschwindigkeit')[0].value;
    zeit 		= entfernung1 / max_ges;
    
    //wochentagsarray
    Wochentag = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	
    //Aktuelle Zeit bestimmen
    jetzt = new Date();					//Objekt erzeugen
    time = jetzt.getTime();				//aktuelle Zeit ermitteln
    
    //Ankunft setzen
	jetzt.setTime(time+(zeit*3600*1000));
	ankunft_zeit = Wochentag[jetzt.getDay()] + ', ' + (jetzt.getMonth()+1) + '.' + jetzt.getDate() + '.' + jetzt.getFullYear() + ' ' + jetzt.getHours() + ':' + jetzt.getMinutes() + ':' + jetzt.getSeconds();	
	
	//Rückkehr setzen
	jetzt.setTime(time+(zeit*3600*1000*2));
	rueckkehr_zeit =  Wochentag[jetzt.getDay()] + ', ' + (jetzt.getMonth()+1) + '.' + jetzt.getDate() + '.' + jetzt.getFullYear() + ' ' + jetzt.getHours() + ':' + jetzt.getMinutes() + ':' + jetzt.getSeconds();	
    
	//Werte setzen
	document.getElementById('entfernung').innerHTML = entfernung1 + ' km';
	document.getElementById('dauer').innerHTML = zeit + ' h';
	document.getElementById('ankunft').innerHTML = ankunft_zeit;
	document.getElementById('rueckkehr').innerHTML = rueckkehr_zeit;
}

function timer(sekunden, finish)
{	
	var minuten = 0;
	var stunden = 0;
	var tage = "";
	//Ausgabe hübsch darstellen!
	if( sekunden < 0 )	//Ist Gebäude fertig?
	{
		rueckgabe = finish;
	}
	else				//Gebäude wird noch gebaut
	{
		//Verbleibende Sekunden?
		if( sekunden > 59 )
		{
			minuten = Math.floor(sekunden / 60 );
			sekunden = sekunden - minuten * 60;
		}
		//VErbleibende Stunden?
		if( minuten > 59 )
		{
			stunden = Math.floor(minuten / 60);
			minuten = minuten - stunden * 60;
		}
		//Verbleibende Stunden
		if( stunden > 23 )
		{
			tage = Math.floor(stunden / 24);
			stunden = stunden - tage*24;
		}
		//Sind minuten, tage oder stunden < 10 ?
		if( sekunden < 10 )
		{
			sekunden = "0" + sekunden;
		}
		if( minuten < 10 )
		{
			minuten = "0" + minuten;
		}
		if( stunden < 10 )
		{
			stunden = "0" + stunden;
		}
		//Sind Tage größer als 1?
		if( tage > 1 )
		{
			tage = tage + " Tage";
		}
		else if( tage > 0 )
		{
			tage = tage + " Tag";
		}
		else
		{
			tage = "";
		}
		rueckgabe = tage + " " + stunden + ":" + minuten + ":" + sekunden;
	}
	return rueckgabe;
}

//TimerFunktion
//Anzahl gibit die Anzahl der elemente an, die gecountdownt werden müssen ;)
function CountDown(anzahl)
{
	for( i=1; i<=anzahl; i++ )
	{
		//alert("Durchlaufe das " + i + ".Element");
		auftrag = document.getElementById("auftrag" + i);	//Feld ansprechen
		sekunden = auftrag.title;							//SekundenAnzahl ermittelt!
		
		//Ausgabe setzen
		auftrag.innerHTML = timer(sekunden, 'fertig');
		//Eine Sekunde verringern
		auftrag.title--;
	}
	//Funktion erneut aufrufen
	window.setTimeout("CountDown("+anzahl+")", 1000);
}

//Stellt den Timer für die Konstruktionsdarstellung da!
function CountDownGeb(ID)
{
	//Sekundenanzahl ermitteln
	auftrag = document.getElementById(ID);				//Lade Element
	sekunden = auftrag.title;							//SekundenAnzahl ermittelt!

	//Ausgabe setzen
	text = timer(sekunden, 'Konstruktion abgeschlossen<br><a href="gebaeude.php">weiter</a>');
	
	//Eine Sekunde verringern
	auftrag.title--;
	
	if( sekunden < 0 )
	{
		auftrag.innerHTML = text;
	}
	else
	{
		auftrag.innerHTML = text + '<br><a href="gebaeude.php?stop_bau=' + ID + '">abbrechen</a>';
	}
	
	//Funktion erneut aufrufen
	window.setTimeout("CountDownGeb("+ ID + ")", 1000);
}

//Stellt den Timer für die Konstruktionsdarstellung da!
function CountDownKolonie()
{
	//Sekundenanzahl ermitteln
	auftrag = document.getElementById('kolonieupgrade');//Lade Element
	auftrag2 = document.getElementById('kolonieupgrade2');
	sekunden = auftrag.title;							//SekundenAnzahl ermittelt!

	//Ausgabe setzen
	text = timer(sekunden, 'Expansion beendet<br><a href="geb_hq.php">weiter</a>');
	
	//Eine Sekunde verringern
	auftrag.title--;
	
	if( sekunden < 0 )
	{
		auftrag2.innerHTML = text;
	}
	else
	{
		auftrag.innerHTML = text;
		auftrag2.innerHTML = '<a href="geb_hq.php?stop=true">abbrechen</a>';
	}
	
	//Funktion erneut aufrufen
	window.setTimeout("CountDownKolonie()", 1000);
}

//Stellt den Timer für die Forschungsdarstellung bereit
function CountDownForschung(ID, type)
{
	//Sekundenanzahl ermitteln
	auftrag = document.getElementById(ID);				//Lade Element
	sekunden = auftrag.title;							//SekundenAnzahl ermittelt!
	
	//Ausgabe setzen
	text = timer(sekunden, 'abgeschlossen <a href="techtree.php?type='+type+'">weiter</a>');
	
	//Eine Sekunde verringern
	auftrag.title--;
	
	if( sekunden < 0 )
	{
		auftrag.innerHTML = text;
	}
	else
	{
		auftrag.innerHTML = text + ' <a href="techtree.php?type='+type+'&stop=' + ID + '">abbrechen</a>';
	}
	
	//Funktion erneut aufrufen
	window.setTimeout("CountDownForschung("+ ID + ", "+type+")", 1000);
}

//CountdownFunktion des Rohstoffgebäudes
function CountDownVorkommen(anzahl)
{
	for( i=1; i<=anzahl; i++ )
	{
		//Sekundenanzahl ermitteln
		auftrag = document.getElementById("vorkommen" + i + "");//Lade Element
		sekunden = auftrag.title;								//SekundenAnzahl ermittelt!
			
		//Ausgabe setzen
		auftrag.innerHTML = timer(sekunden, 'ja');
		//Eine Sekunde verringern
		auftrag.title--;
	}
	
	//Funktion erneut aufrufen
	window.setTimeout("CountDownVorkommen("+ anzahl + ")", 1000);	
}

//Stellt den Timer für die Forschungsdarstellung bereit
function CountDownForscher(ID)
{
	//Sekundenanzahl ermitteln
	auftrag = document.getElementById(ID);				//Lade Element
	sekunden = auftrag.title;							//SekundenAnzahl ermittelt!
	
	//Ausgabe setzen
	text = timer(sekunden, 'Ausbildung abgeschlossen<br><a href="geb_forschungszentrale.php">weiter</a>');
	
	//Eine Sekunde verringern
	auftrag.title--;
	
	if( sekunden < 0 )
	{
		auftrag.innerHTML = text;
	}
	else
	{
		auftrag.innerHTML = text + '<br><a href="geb_forschungszentrale.php?stop=' + ID + '">abbrechen</a>';
	}
	
	//Funktion erneut aufrufen
	window.setTimeout("CountDownForscher("+ ID + ")", 1000);
}

//Stellt den Timer für die Einheitendarstellung bereit
function CountDownEinheiten(anzahl)
{
	for( i=1; i<=anzahl; i++ )
	{
		//alert("Durchlaufe das " + i + ".Element");
		auftrag = document.getElementById("einheit" + i);	//Feld ansprechen
		sekunden = auftrag.title;							//SekundenAnzahl ermittelt!
		
		//Ausgabe setzen
		auftrag.innerHTML = timer(sekunden, 'fertig');
		//Eine Sekunde verringern
		auftrag.title--;
	}
	//Funktion erneut aufrufen
	window.setTimeout("CountDownEinheiten("+anzahl+")", 1000);
}

//Funktion selektiert bestimmte Einheiten
function selectEinheit(ChassisTyp, BauplanTyp, Anzahl)
{
	//Durchlaufe alle Einheitenelemente
	for(i=0; i<Anzahl; i++)
	{
		//Einheit laden
		einheit = document.getElementById(i);
		
		//Lade EinheitenWerte
		werte = einheit.value;
		//Zerteile Werte in ChassisTyp und Bauplantyp
		werte = werte.split("|");
		
		//ChassisTyp setzen
		einheit_chassistyp = werte[0];
		//BauplanTyp setzen
		einheit_bauplantyp = werte[1];
		
		//Nun überprüfen ob der Chassistyp gesetzt werden soll oder der BauplanTyp?
		einheit.checked = false;
		if( ChassisTyp == einheit_chassistyp || BauplanTyp == einheit_bauplantyp)
		{
			einheit.checked = true;
		}
	}	 	
}

//Auswahl aufheben
function delSelection(Anzahl)
{
	for(i=0; i<Anzahl; i++)
	{
		document.getElementById(i).checked = false;
	}
}

//Selektiert alle Einheiten
function selectAll(Anzahl)
{
	for(i=0; i<Anzahl; i++)
	{
		document.getElementById(i).checked = true;
	}
}

/*Funktion fügt einen Smiley dorthin, wo der Mauszeiger sich befindet*/
function addSmiley(kuerzel)
{
	this.document.forms[0].inhalt.value += kuerzel+" ";
    this.document.forms[0].inhalt.focus();
}