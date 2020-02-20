/*Kampfbericht.cpp
Diese Datei stellt Funktionen zur Kampfberichterstellung bereit

History:
		27.09.2004		MvR		created
*/
#include "kampfbericht.h"

// Eine Datei laden
void speichereBericht(int iID_Angreifer,int iID_Verteidiger, int iWinner, int *piErgebnis, KAMPF *pKampf, int iBauplanAngreifer, int iBauplanVerteidiger, int *piAnzahlBauplaeneEinheiten, int *piBauplanArray, DATENBANK *pDB, int *piOutputDatei, int *piOutputDatei2, int iDatumDesKampfes, int *piRohstoffDetails)
{
	#if URL == 2	//offline-Modus!
		char szDatei[200] 		= "../spiel/templates/kampfbericht.tpl";			//Kampfberichtdatei
		char szDateiDetail[200] = "../spiel/templates/kampfbericht_detail.tpl";		//Kampfbericht-Detail datei
	#else
		char szDatei[200] 		= "/srv/www/htdocs/subterranwar/spiel/templates/kampfbericht.tpl";			//Kampfberichtdatei
		char szDateiDetail[200] = "/srv/www/htdocs/subterranwar/spiel/templates/kampfbericht_detail.tpl";	//Kampfbericht-Detail datei
	#endif
	
	//Deklarationen
	char szQuery[250];
	//char szDatei[200] 		= "../spiel/templates/kampfbericht.tpl";			//Kampfberichtdatei
	//char szDateiDetail[200] = "..//spiel/templates/kampfbericht_detail.tpl";	//Kampfbericht-Detail datei
	char szKoordinaten[25];		//String welcher die Koordinaten des Verteidigers speichert
	char szKoordinaten2[25];	//String, welcher die Koordinaten des Verteidigers speichert
	int iMax=0;					//Diese Variabel speichert die größte Einheiten Anzahl
	int iI=0;					//Zählvariabel
	int pos=0;					//Gibt die Position an, an welcher Stelle der Suchstring beginnt
	char ubertrag[10];			// Nimmet die zahlen von sprintf entgegen.
	string ergebnis;
	MYSQL_ROW dbRow;			//MYSQL-Ergebnis Struktur
	MYSQL_ROW dbRow2;			//MYSQL-Ergebnis Struktur
	ifstream daten(szDatei);	//Kampfberichtdatei wird geöffnet
	ifstream details(szDateiDetail);	//KampfberichtDetail wird geöffnet
	string komplett;			//Kampfbericht des Angreifers
	string komplett2;			//Kampfbericht des Verteidigers
	string sdetails;			//Hier steht nacher das Kampfbericht-Detail.tpl drin
	char szSuchstring[20];		//String wird für die Rohstoffanzeige benötigt. Dort wird der zu suchende RohstoffTeil gespeichert (z.B. %RES1%)
	char buffer[5000];			//Hier werden alle Daten aus der Datei zwischengespeichert
	
	// Die Übersichtsdatei öffnen
	if( daten.is_open() )
	{
		//Daten einlesen	
		do
		{
			daten.getline(buffer,4999);
			komplett+=buffer;
			komplett+="\n";
		}while(!daten.eof());
		daten.close();					//Datei schließen
	}
	
	// Die Detaildatei öffnen und einlesen
	if( details.is_open() )
	{
		do
		{
			details.getline(buffer,4999);
			sdetails+=buffer;		
			sdetails+="\n";
		}
		while(!details.eof());
		details.close();				//Datei schließen
	}

	//Angreifer Koordinaten und Name laden
	sprintf(szQuery, "SELECT t_user.Nickname, t_kolonie.Bezeichnung, t_koordinaten.X, t_koordinaten.Y, t_koordinaten.Z FROM t_user, t_kolonie, t_koordinaten WHERE t_user.ID_User = t_kolonie.ID_User AND t_kolonie.ID_Koordinaten = t_koordinaten.ID_Koordinaten AND t_user.ID_User = %d", iID_Angreifer);
	pDB->query(szQuery);
	dbRow = mysql_fetch_row(pDB->dbResult);
	
	//Verteidiger Koordinaten und Name laden
	sprintf(szQuery, "SELECT t_user.Nickname, t_kolonie.Bezeichnung, t_koordinaten.X, t_koordinaten.Y, t_koordinaten.Z FROM t_user, t_kolonie, t_koordinaten WHERE t_user.ID_User = t_kolonie.ID_User AND t_kolonie.ID_Koordinaten = t_koordinaten.ID_Koordinaten AND t_user.ID_User = %d", iID_Verteidiger);
	pDB->query(szQuery);
	dbRow2 = mysql_fetch_row(pDB->dbResult);

	/*Bestimmen welche EinheitenAnzahl größer ist
	 * Die vom Verteidiger, oder die vom Angreifer?*/
	if( pKampf->getAnzahlAngreifer() > pKampf->getAnzahlVerteidiger() )
	{
		iMax = pKampf->getAnzahlAngreifer();		//AngreiferAnzahl = iMax
	}
	else
	{
		iMax = pKampf->getAnzahlVerteidiger();		//VerteidigerAnzahl = iMax
	}

	/*Text suchen und ersetzen*/
	pos=komplett.find("%A_KOORDS%"); 		//first zeigt auf den punkt im Text, der zu ersetzen ist.
	sprintf(szKoordinaten, "%s:%s:%s", dbRow[2], dbRow[3], dbRow[4]);
	string einfuegen = szKoordinaten; 			//Hier den Koordinatenstring eingeben
	komplett.replace(pos,10,einfuegen); 		//Hier wird ersetzt.

	pos=komplett.find("%V_KOORDS%");
	sprintf(szKoordinaten2, "%s:%s:%s", dbRow2[2], dbRow2[3], dbRow2[4]);
	einfuegen = szKoordinaten2;					//Hier die Verteidigerkoords verlinken
	komplett.replace(pos,10,einfuegen);

	//Gesamt
	pos=komplett.find("%A_ANZ_W%");
	sprintf(ubertrag,"%d", getProzentWert(iMax, pKampf->getAnzahlAngreifer()));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);
	
	pos=komplett.find("%A_ANZ%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlAngreifer());		//Hier den wert eintragen
	komplett.replace(pos,7,ubertrag);

	pos=komplett.find("%V_ANZ_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlVerteidiger()));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_ANZ%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlVerteidiger());		//Hier den wert eintragen
	komplett.replace(pos,7,ubertrag);

	//Infanteristen
	pos=komplett.find("%A_ANZ1_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax,pKampf->getAnzahlTypAngreifer(1)));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);
	
	pos=komplett.find("%A_ANZ1%");
	sprintf(ubertrag,"%d", pKampf->getAnzahlTypAngreifer(1));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	pos=komplett.find("%V_ANZ1_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypVerteidiger(1)));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%V_ANZ1%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypVerteidiger(1));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	//Fahrzeuge
	pos=komplett.find("%A_ANZ2_W%");
	sprintf(ubertrag,"%d", getProzentWert(iMax, pKampf->getAnzahlTypAngreifer(2)));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%A_ANZ2%");
	sprintf(ubertrag,"%d", pKampf->getAnzahlTypAngreifer(2));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	pos=komplett.find("%V_ANZ2_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypVerteidiger(2)));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%V_ANZ2%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypVerteidiger(2));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	//Mechs
	pos=komplett.find("%A_ANZ3_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypAngreifer(3)));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);
	
	pos=komplett.find("%A_ANZ3%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypAngreifer(3));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	pos=komplett.find("%V_ANZ3_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypVerteidiger(3)));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%V_ANZ3%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypVerteidiger(3));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	//Verloren Gesamt
	pos=komplett.find("%A_LOST_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlAngreifer()-pKampf->getAnzahlAngreiferLebend())));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%A_LOST%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlAngreifer()-pKampf->getAnzahlAngreiferLebend()));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	pos=komplett.find("%V_LOST_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlVerteidiger()-pKampf->getAnzahlVerteidigerLebend())));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%V_LOST%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlVerteidiger()-pKampf->getAnzahlVerteidigerLebend()));		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	//Infanteristen
	pos=komplett.find("%A_LOST1_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlTypAngreifer(1)-pKampf->getAnzahlTypAngreiferLebend(1))));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%A_LOST1%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlTypAngreifer(1)-pKampf->getAnzahlTypAngreiferLebend(1)));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_LOST1_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlTypVerteidiger(1)-pKampf->getAnzahlTypVerteidigerLebend(1))));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%V_LOST1%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlTypVerteidiger(1)-pKampf->getAnzahlTypVerteidigerLebend(1)));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

 
	//Fahrzeuge
	pos=komplett.find("%A_LOST2_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlTypAngreifer(2)-pKampf->getAnzahlTypAngreiferLebend(2))));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%A_LOST2%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlTypAngreifer(2)-pKampf->getAnzahlTypAngreiferLebend(2)));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_LOST2_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlTypVerteidiger(2)-pKampf->getAnzahlTypVerteidigerLebend(2))));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%V_LOST2%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlTypVerteidiger(2)-pKampf->getAnzahlTypVerteidigerLebend(2)));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	//Mechs
	pos=komplett.find("%A_LOST3_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlTypAngreifer(3)-pKampf->getAnzahlTypAngreiferLebend(3))));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%A_LOST3%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlTypAngreifer(3)-pKampf->getAnzahlTypAngreiferLebend(3)));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_LOST3_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, (pKampf->getAnzahlTypVerteidiger(3)-pKampf->getAnzahlTypVerteidigerLebend(3))));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%V_LOST3%");
	sprintf(ubertrag,"%d",(pKampf->getAnzahlTypVerteidiger(3)-pKampf->getAnzahlTypVerteidigerLebend(3)));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	//Verbleibend Gesamt:
	pos=komplett.find("%A_LEFT_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlAngreiferLebend()));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%A_LEFT%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlAngreiferLebend());		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	pos=komplett.find("%V_LEFT_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlVerteidigerLebend()));		//Hier den wert eintragen
	komplett.replace(pos,10,ubertrag);

	pos=komplett.find("%V_LEFT%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlVerteidigerLebend());		//Hier den wert eintragen
	komplett.replace(pos,8,ubertrag);

	//Infanterie
	pos=komplett.find("%A_LEFT1_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypAngreiferLebend(1)));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);
	
	pos=komplett.find("%A_LEFT1%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypAngreiferLebend(1));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_LEFT1_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypVerteidigerLebend(1)));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%V_LEFT1%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypVerteidigerLebend(1));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	//Fahrzeuge
	pos=komplett.find("%A_LEFT2_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypAngreiferLebend(2)));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%A_LEFT2%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypAngreiferLebend(2));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_LEFT2_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypVerteidigerLebend(2)));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%V_LEFT2%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypVerteidigerLebend(2));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	//Mechs
	pos=komplett.find("%A_LEFT3_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypAngreiferLebend(3)));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%A_LEFT3%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypAngreiferLebend(3));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	pos=komplett.find("%V_LEFT3_W%");
	sprintf(ubertrag,"%d",getProzentWert(iMax, pKampf->getAnzahlTypVerteidigerLebend(3)));		//Hier den wert eintragen
	komplett.replace(pos,11,ubertrag);

	pos=komplett.find("%V_LEFT3%");
	sprintf(ubertrag,"%d",pKampf->getAnzahlTypVerteidigerLebend(3));		//Hier den wert eintragen
	komplett.replace(pos,9,ubertrag);

	//Wer ist gewinner?
	if( iWinner == 1 )
	{
		einfuegen = szKoordinaten;		//angreifer hat gewonnen
	}
	else
	{
		einfuegen = szKoordinaten2;		//Verteidiger hat gewonnen
	}

	//Setze Gewinner
	pos=komplett.find("%WINNER_KOORDS%");
	komplett.replace(pos,15,einfuegen);	

	/*Rohstoffdetails anzeigen!*/
	for(iI=1; iI<=14; iI++)
	{
		//Setze Suchstring
		sprintf(szSuchstring, "%%RES%d%%", iI);
		//Ermittle Position an der sich die Daten befinden
		pos = komplett.find(szSuchstring);
		//Schreibe zu ersetzenden Wert in string
		sprintf(ubertrag, "%d", piRohstoffDetails[iI]);
		//Ersetze Suchstring durch Wert!
		komplett.replace(pos, strlen(szSuchstring), ubertrag);
	}
	
	/*Den Kampfdetailstring erzeugen*/
	//Bericht für Angreifer erzeugen
	komplett2 = komplett;
	ergebnis = setzeKampfDetailBereich(piErgebnis, piBauplanArray, piAnzahlBauplaeneEinheiten, iBauplanAngreifer, pDB, sdetails, iMax);
	pos=komplett.find("%KAMPFBERICHT_DETAIL%");
	komplett.replace(pos,21,ergebnis);
	
	//Bericht für Verteidiger erzeugen!
	ergebnis = setzeKampfDetailBereich((piErgebnis+iBauplanAngreifer*8), (piBauplanArray+iBauplanAngreifer), (piAnzahlBauplaeneEinheiten+iBauplanAngreifer), iBauplanVerteidiger, pDB, sdetails, iMax);
	pos=komplett2.find("%KAMPFBERICHT_DETAIL%");
	komplett2.replace(pos,21,ergebnis);
	
	/*Berichtsdaten eintragen*/
	//Daten für Angreifer speichern
	string sQuery;
	sQuery = "INSERT INTO t_bericht (INHALT) VALUES ('" + komplett + "')";
	pDB->query(&sQuery[0]);
	//ID laden
	*(piOutputDatei) = pDB->insert_id();
	//Datum setzen
	sprintf(&sQuery[0], "UPDATE t_bericht SET Datum = %d WHERE ID_Bericht = %d", iDatumDesKampfes, *(piOutputDatei));
	pDB->query(&sQuery[0]);

	//Daten für Verteidiger speichern
	sQuery = "INSERT INTO t_bericht (INHALT) VALUES ('" + komplett2 + "')";
	pDB->query(&sQuery[0]);
	//ID laden
	*(piOutputDatei2) =  pDB->insert_id();
	sprintf(&sQuery[0], "UPDATE t_bericht SET Datum = %d WHERE ID_Bericht = %d", iDatumDesKampfes, *(piOutputDatei2));
	pDB->query(&sQuery[0]);
}

string setzeKampfDetailBereich(int *piErgebnis, int *piBauplanArray, int *piAnzahlBauplanEinheiten, int iAnzahlBauplaene, DATENBANK *pDB, string sdetails, int iMax)
{
	//Deklarationen
	char szQuery[250];			//QueryVariabel
	int iI=0;					//Zählvariabel
	int pos;					//Integer wert, der ne position in einem String angibt!
	char ubertrag[10];			//Nimmt zahlen von sprintf entgegen
	string umwandel=sdetails;	// sdetails ist die feste basis, umwandel der zu bearbeitende string
	string ergebnis;			// ergebnis speichert Den endgültigen string
	MYSQL_ROW dbRow;			//Mysql-Ergebnis!
	
	//Durchlaufe alle Baupläne
	for(iI=0; iI<iAnzahlBauplaene; iI++)
	{
		//Name und Anzahl setzen
		pos=umwandel.find("%ANZAHL%");
		sprintf(ubertrag,"%d",*(piAnzahlBauplanEinheiten+iI));		// Anzahl der Eigenen Einheiten statt 5
		umwandel.replace(pos,8,ubertrag);
	
		//Name aus Datenbank holen!
		sprintf(szQuery, "SELECT Bezeichnung FROM t_profil WHERE ID_Profil = %d", *(piBauplanArray+iI));
		pDB->query(szQuery);
		dbRow = mysql_fetch_row(pDB->dbResult);
		
		//Name setzen
		pos=umwandel.find("%NAME%");
		umwandel.replace(pos,6,dbRow[0]); //Statt hallo den namensstring
	
		/*Infanteristen*/
		//Setze Angreifer Werte
		pos=umwandel.find("%A_ANZ_W%");
		sprintf(ubertrag,"%d",getProzentWert(iMax, *(piErgebnis+(iI*8)+(0*4)+1)));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,9,ubertrag);
	
		pos=umwandel.find("%A_ANZ%");
		sprintf(ubertrag,"%d",*(piErgebnis+(iI*8)+(0*4)+1));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,7,ubertrag);
		
		//Setze Verteidiger Werte
		pos=umwandel.find("%V_ANZ_W%");
		sprintf(ubertrag,"%d",getProzentWert(iMax, *(piErgebnis+(iI*8)+(1*4)+1)));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,9,ubertrag);
	
		pos=umwandel.find("%V_ANZ%");
		sprintf(ubertrag,"%d",*(piErgebnis+(iI*8)+(1*4)+1));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,7,ubertrag);
		
		/*Fahrzeuge*/
		//Setze AngreiferWerte
		pos=umwandel.find("%A_ANZ1_W%");
		sprintf(ubertrag,"%d",getProzentWert(iMax, *(piErgebnis+(iI*8)+(0*4)+2)));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,10,ubertrag);
	
		pos=umwandel.find("%A_ANZ1%");
		sprintf(ubertrag,"%d",*(piErgebnis+(iI*8)+(0*4)+2));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,8,ubertrag);
	
		//Setze Verteidiger Werte
		pos=umwandel.find("%V_ANZ1_W%");
		sprintf(ubertrag,"%d",getProzentWert(iMax, *(piErgebnis+(iI*8)+(1*4)+2)));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,10,ubertrag);
	
		pos=umwandel.find("%V_ANZ1%");
		sprintf(ubertrag,"%d",*(piErgebnis+(iI*8)+(1*4)+2));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,8,ubertrag);
	
		/*Mechs*/
		//Setze AngreiferWerte
		pos=umwandel.find("%A_ANZ2_W%");
		sprintf(ubertrag,"%d",getProzentWert(iMax, *(piErgebnis+(iI*8)+(0*4)+3)));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,10,ubertrag);
	
		pos=umwandel.find("%A_ANZ2%");
		sprintf(ubertrag,"%d",*(piErgebnis+(iI*8)+(0*4)+3));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,8,ubertrag);
	
		//Setze VerteidigerWerte
		pos=umwandel.find("%V_ANZ2_W%");
		sprintf(ubertrag,"%d",getProzentWert(iMax, *(piErgebnis+(iI*8)+(1*4)+3)));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,10,ubertrag);
	
		pos=umwandel.find("%V_ANZ2%");
		sprintf(ubertrag,"%d",*(piErgebnis+(iI*8)+(1*4)+3));		// Hier Variable statt 5 einfügen
		umwandel.replace(pos,8,ubertrag);
		
		//Rückgabestring vorbereiten
		ergebnis+=umwandel;	//Den fertigen string weitergeben
		ergebnis+="\n";		//Zeilenumbruch
		umwandel=sdetails;	// umwandel zurück auf basisniveau setzen
	}
	//Rückgabe
	return ergebnis;
}

/*funktion berechnet einen Prozentualen Satz!*/
int getProzentWert(int iMax, int iWert)
{
	//Deklarationen
	double dMaxWidth = 200;		//Gibt die Breite des Anzeigebalkens in Pixel an!
	
	//Berechnung
	dMaxWidth = (dMaxWidth / iMax) * iWert;
	return (int)dMaxWidth;
}
