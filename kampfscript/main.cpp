#include <stdio.h>
#include <mysql/mysql.h>
#include "db.h"
#include "einheiten.h"
#include "kampf.h"
#include "zufall.h"
#include "kampfbericht.h"

using namespace std;

//Deklarationen
int main(int argc, char* args[]);


/*Main-Funktion
 * szArgs[0] = Scriptname
 * szArgs[1] = MissionsID*/
int main(int iArgc, char* szArgs[])
{
	//Deklarationen
	int iError 				= 0;	//Rückgabevariabel <= 0 => fehler
	int iI 					= 0;	//Zählvariabel
	int iA					= 0;	//Zählvariabel
	int iReturn 			= 0;	//Wird für z.b. das Kampfscript benötigt
	int iFirstAttack 		= 1;	//Wer schiesst bei einem Kampf als 1.
	int iWinner 			= 0;	//Wer hat einen Kampf gewonnen
	int iAnzahlAngreifer 	= 0;	//wie viele Einheiten kämpfen gegen wen?
	int iAnzahlVerteidiger 	= 0;	//wie viele Einheiten kämpfen gegen wen?
	int iAnzahlTypen_Angreifer[4] = {0,0,0,0};
	int iAnzahlTypen_Verteidiger[4] = {0,0,0,0};
	int *piAnzahlBauplanEinheiten;	
	int iID_Mission = 0;			//Id der Mission
	int iID_Kolonie = 0;			//KolonieID um flotten z.b. verlegen zu können
	int iID_Kolonie2 = 0;
	int iAnzahlBauplaeneAngreifer;	//Anzahl baupläne angreifer
	int iAnzahlBauplaeneVerteidiger;//Anzahl baupläne verteidiger
	int *piBauplanUebersetzung;		//2 Dimensionales Array welches Verteidiger und Angreifer BaupläneIDs speichert
	int *piErgebnis;				/*Array, welches die Ergebnisse speichert!
									piErgebnis[3][AnzahlBaupläne][2][4]
									piErgebnis[3] 	0 = undefined
													1 = Angreifer
													2 = Verteidiger
									piErgebnis[3][AnzahlBaupläne][2]	0 = Angreifer
																		1 = Verteidiger
									piErgebnis[3][iAnzahlBaupläne][4]	0 = undefined
																		1 = Infanterist
																		2 = Fahrzeug
																		3 = Mech*/											
	char szQuery[400];				//Queryvariabel für Datenbank anfragen!
	char *pszRessources[14];		//Zeiger auf String für die Ressourcen!
	char *pszRessourcesDetail[2];	//Ist für die genaueren Daten der Ressourcen zuständig!
	char szTopic[100];				//String, welcher für Ereignismeldungen später den Betreff darstellt
	char szInhalt[10000];			//Zeiger auf Inhaltsvariabel.
	int *piKampfberichtID = new int;//Wie heisst der Dateiname bei einem Kampfbericht?
	int *piKampfberichtID2 = new int;//Dateiname des Verteidigers!
	int iLadeKapazitaet = 0;		//Gibt die Ladekapazitaet der Flotte des Angreifers an :)
	int iRohstoffeLeft = 0;			//Wie viele Rohstoffe hat der Verteidiger noch übrig?
	int iZufall = 0;				//Zufallsvariabel für RohstoffID-Bestimmung :)
	//double dRohstoffAnzahl = 0;
	int iRohstoffAnzahl = 0;		//Wie viele Rohstoffe werden dem Verteidiger abgezogen?
	int iKampfberichtRohstoffe[15];	//Welche Rohstoffe wurden geklaut?
	char szRohstoffe[1000];			//String, welcher nach einem Kampf die Rohstoffe setzt!
	DATENBANK *pDB;					//Datenbankobjekt
	DATENBANK *pDB2;				//Datenbankobjekt2
	MYSQL_ROW dbRow;				//MYSQL-Ergebnis Variabel
	MYSQL_ROW dbRow2;				//MYSQL-Ergebnis Variabel
	EINHEIT *pAngreifer;			//Array auf angreifer
	EINHEIT *pVerteidiger;			//Array auf Verteidiger
	KAMPF *pKampf;					//Zeiger für kampfobjekt :)
	
	//Übergabeparameter überprüfen
	if( iArgc == 2  )
	{
		//DAtenbankobjekt erzeugen
		pDB = new DATENBANK();
		
		//Lade mission
		sprintf(szQuery, "SELECT ID_Mission, Parameter, ID_Flotte, ID_KoordinatenDestination, ID_User, ID_UserOpfer, Ressources, ID_KoordinatenSource, Hinflug FROM t_mission WHERE ID_Mission = %d", atoi(szArgs[1]));
		pDB->query(szQuery);
		dbRow = mysql_fetch_row(pDB->dbResult);
		
		//MissionsID setzen
		iID_Mission = atoi(dbRow[0]);
		
		//Lade KolonieID
		sprintf(szQuery, "SELECT ID_Kolonie FROM t_kolonie WHERE ID_Koordinaten = %d", atoi(dbRow[7]));
		pDB->query(szQuery);
		dbRow2 = mysql_fetch_row(pDB->dbResult);
		iID_Kolonie = atoi(dbRow2[0]);		//KolonieID des Angreifers
		
		sprintf(szQuery, "SELECT ID_Kolonie FROM t_kolonie WHERE ID_Koordinaten = %d", atoi(dbRow[3]));
		pDB->query(szQuery);
		dbRow2 = mysql_fetch_row(pDB->dbResult);
		iID_Kolonie2 = atoi(dbRow2[0]);		//KolonieId des Verteidigers
		
		//Parameter überprüfen
		/*Angreifen*/
		if( 0 == strcmp( dbRow[1], "Angriff") )
		{
			//2. Datenbankobjekt einleiten
			pDB2 = new DATENBANK();
			
			//Lade Angreiferanzahl
			sprintf(szQuery, "SELECT COUNT(*) FROM t_einheit, t_profil WHERE ID_Flotte = %d AND t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp <= 3", atoi(dbRow[2]));
			pDB->query(szQuery);
			dbRow2 = mysql_fetch_row(pDB->dbResult);
			
			//Setze Angreiferanzahl
			iAnzahlAngreifer = atoi(dbRow2[0]);
			
			//Lade Verteidigeranzahl
			sprintf(szQuery, "SELECT COUNT( * ) FROM t_profil, t_einheit LEFT JOIN t_mission USING ( ID_Flotte )  WHERE t_profil.ID_Profil = t_einheit.ID_Bauplan AND t_profil.ChassisTyp <= 3 AND t_mission.ID_Flotte is NULL AND t_einheit.ID_User = %d", atoi(dbRow[5])); 
			pDB->query(szQuery);
			dbRow2 = mysql_fetch_row(pDB->dbResult);
			
			//Setze Verteidigeranzahl
			iAnzahlVerteidiger = atoi(dbRow2[0]);
			
			/*Anzahl baupläne ermitteln LKWs und Truppentransporter aussen vor lassen!*/
			//Angreifer				
			sprintf(szQuery, "SELECT COUNT(DISTINCT ID_Bauplan) FROM t_einheit,t_profil WHERE t_einheit.ID_Flotte = %d AND t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp <= 3", atoi(dbRow[2]));
			pDB->query(szQuery);
			dbRow2 = mysql_fetch_row(pDB->dbResult);
			
			//ANzahl AngreiferBaupläne setzen
			iAnzahlBauplaeneAngreifer = atoi(dbRow2[0]);
			
			//Verteidiger
			sprintf(szQuery, "SELECT COUNT(DISTINCT ID_Bauplan ) FROM t_profil, t_einheit LEFT JOIN t_mission USING ( ID_Flotte )  WHERE t_mission.ID_Flotte is NULL AND t_einheit.ID_User = %d AND t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_profil.ChassisTyp <= 3", atoi(dbRow[5]));
			pDB->query(szQuery);
			dbRow2 = mysql_fetch_row(pDB->dbResult);
			
			//Anzahl VerteidigerBaupläne setzen
			iAnzahlBauplaeneVerteidiger = atoi(dbRow2[0]);

			//Welcher Wrt ist größer?
			piBauplanUebersetzung 	= (int*)malloc(sizeof(int)*iAnzahlBauplaeneAngreifer + (sizeof(int)*iAnzahlBauplaeneVerteidiger));
			piErgebnis				= (int*)malloc(sizeof(int)*3*iAnzahlBauplaeneAngreifer*2*4 + (sizeof(int)*3*iAnzahlBauplaeneVerteidiger*2*4));
			piAnzahlBauplanEinheiten= (int*)malloc(sizeof(int)*iAnzahlBauplaeneAngreifer + (sizeof(int)*iAnzahlBauplaeneVerteidiger));
						
			/*piErgebnis vollschreiben auf 0 setzen!*/
			for(iI=0; iI<iAnzahlBauplaeneAngreifer; iI++ )
			{
				*(piErgebnis+0*8+iI*8+0*4+0) = 0;	
				*(piErgebnis+0*8+iI*8+0*4+1) = 0;
				*(piErgebnis+0*8+iI*8+0*4+2) = 0;
				*(piErgebnis+0*8+iI*8+0*4+3) = 0;	
				*(piErgebnis+0*8+iI*8+1*4+0) = 0;	
				*(piErgebnis+0*8+iI*8+1*4+1) = 0;
				*(piErgebnis+0*8+iI*8+1*4+2) = 0;
				*(piErgebnis+0*8+iI*8+1*4+3) = 0;
			}	
			for(iI=0; iI<iAnzahlBauplaeneVerteidiger; iI++ )
			{
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+0*4+0) = 0;	
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+0*4+1) = 0;
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+0*4+2) = 0;
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+0*4+3) = 0;	
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+1*4+0) = 0;	
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+1*4+1) = 0;
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+1*4+2) = 0;
				*(piErgebnis+8*iAnzahlBauplaeneAngreifer+iI*8+1*4+3) = 0;
			}
			
			/*piBauplanübersetzung und piBauplanEinheiten auf 0 setzen!*/
			//Angreifer
			for(iI=0; iI<iAnzahlBauplaeneAngreifer; iI++)
			{
				*(piAnzahlBauplanEinheiten+iI) 	= 0;
				*(piBauplanUebersetzung+iI)		= 0;
				
			}
			//Verteidiger
			for(iI=0; iI<iAnzahlBauplaeneVerteidiger; iI++)
			{
				*(piAnzahlBauplanEinheiten+iI+iAnzahlBauplaeneAngreifer) 	= 0;
				*(piBauplanUebersetzung+iI+iAnzahlBauplaeneAngreifer)		= 0;
			}
					
			/*geplünderte Rohstoffdetails setzen*/
			for(iI=0; iI< 15; iI++)
			{
				iKampfberichtRohstoffe[iI] = 0;
			}
			
			/*Angreifer und Verteidiger laden*/
			//Speicher reservieren
			pAngreifer = (EINHEIT*)malloc(iAnzahlAngreifer * sizeof(EINHEIT));
			pVerteidiger = (EINHEIT*)malloc(iAnzahlVerteidiger * sizeof(EINHEIT));
			
			//Lade Angreifer
			iI = 0;
			sprintf(szQuery, "SELECT t_einheit.ID_Einheit, t_einheit.ID_Bauplan, t_einheit.ID_User, t_profil.ChassisTyp FROM t_einheit, t_profil WHERE t_einheit.ID_Bauplan = t_profil.ID_Profil AND t_einheit.ID_Flotte = %d AND t_profil.ChassisTyp <= 3 ORDER BY t_einheit.ID_Bauplan", atoi(dbRow[2]));
			pDB->query(szQuery);
			
			//Setze Angreiferarray
			iA = -1;
			while( (dbRow2 = mysql_fetch_row(pDB->dbResult)) )
			{
				//Setze Angreifertyp!
				iAnzahlTypen_Angreifer[atoi(dbRow2[3])]++;
								
				//Angreiferarray setzen
				pAngreifer[iI] = EINHEIT(atoi(dbRow2[0]), atoi(dbRow2[2]), atoi(dbRow2[1]), pDB2);
									
				//ID-Bauplan setzen!
				if( iI == 0 || *(piBauplanUebersetzung+iA) != atoi(dbRow2[1]) )
				{
					//iReturn = iI;
					iA++;
					*(piBauplanUebersetzung+iA) = atoi(dbRow2[1]);
				}
				
				//Setze Anzahl Einheiten des Bauplantyps!
				*(piAnzahlBauplanEinheiten+iA)+=1;
				iI++;
			}
									
			//Lade Verteidiger
			iI=0;
			sprintf(szQuery, "SELECT ID_Einheit, ID_Bauplan, t_einheit.ID_User, t_profil.ChassisTyp  FROM t_profil LEFT JOIN t_einheit ON t_profil.ID_Profil = t_einheit.ID_Bauplan LEFT JOIN t_mission USING ( ID_Flotte )  WHERE t_mission.ID_Flotte is NULL AND t_einheit.ID_User = %d AND t_profil.ChassisTyp <= 3 ORDER BY t_einheit.ID_Bauplan", atoi(dbRow[5]));
			pDB->query(szQuery);
			
			//Setze VerteidigerArray
			iA = -1;;
			while( (dbRow2 = mysql_fetch_row(pDB->dbResult)) )
			{
				//Setze Verteidigertyp!
				iAnzahlTypen_Verteidiger[atoi(dbRow2[3])]++;
								
				//VerteidigerAarray setzen
				pVerteidiger[iI] = EINHEIT(atoi(dbRow2[0]), atoi(dbRow2[2]), atoi(dbRow2[1]), pDB2);
				
				//ID-Bauplan setzen!
				if( iI ==0 || *(piBauplanUebersetzung+iA+iAnzahlBauplaeneAngreifer) != atoi(dbRow2[1]) )
				{
					//iReturn = iI;															//Bei welchem Durchlauf tritt Abweichung auf?
					iA++;																	//den Index für die EinheitenanzahlZähler pro ProfilTyp erhöhen
					*(piBauplanUebersetzung+iA+iAnzahlBauplaeneAngreifer) = atoi(dbRow2[1]);//BauplanÜbersetzungsarray setzen	
				}
				//Setze Anzahl Einheiten des Bauplantyps!
				*(piAnzahlBauplanEinheiten+iA+iAnzahlBauplaeneAngreifer)+=1;
				iI++;
			}
			
			//Angreifer
			/*printf("### Debugausgabe: ###\n");
			printf("ID | Anzahl\n");
			for(iI=0; iI<iAnzahlBauplaeneAngreifer; iI++)
			{
				printf("%d | %d\n", *(piBauplanUebersetzung+iI),*(piAnzahlBauplanEinheiten+iI));			
			}
			printf("=============\n");
			//Verteidiger
			for(iI=0; iI<iAnzahlBauplaeneVerteidiger; iI++)
			{
				printf("%d | %d\n",*(piBauplanUebersetzung+iI+iAnzahlBauplaeneAngreifer), *(piAnzahlBauplanEinheiten+iI+iAnzahlBauplaeneAngreifer));
			}*/
			
			//KAmpfobjekt erzeugen und später kampf starten!
			pKampf = new KAMPF(&pAngreifer[0], &pVerteidiger[0], iAnzahlAngreifer, iAnzahlVerteidiger, &iAnzahlTypen_Angreifer[0], &iAnzahlTypen_Verteidiger[0],0, iAnzahlBauplaeneAngreifer, iAnzahlBauplaeneVerteidiger, piErgebnis, piBauplanUebersetzung);
	
			//ÜBerprüfen ob Verteidiger UND Angreifer vorhanden sind!!!
			if( iAnzahlVerteidiger > 0 && iAnzahlAngreifer > 0 )
			{
				//Führe Kampf aus!	
				iReturn = 0;		
				do
				{
					//Starte eine Kampfrunde
					iReturn = pKampf->starteKampf(iFirstAttack);
					
					//Wenn Kampf zu ende, dann iWinner setzen!
					if( iReturn == -1 )
					{
						iWinner = iFirstAttack;
					}
					
					/*Immer Angreifer und Verteidiger im Rythmus ändern. weil iFirstAttack 
					gibt an, wer gerade schiessen darf!*/
					if( iFirstAttack == 2 )
					{
						iFirstAttack = 0;
					}
					iFirstAttack++;
				}while( iReturn != -1 );
				
				//ergebnis anzeigen!
				/*for( iI=0; iI<iAnzahlBauplaeneAngreifer; iI++ )
				{
					printf("Getötet inf:%d\n", *(piErgebnis+(0*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(0*4)+1));
					printf("Getötet fahrzeuge:%d\n",*(piErgebnis+(0*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(0*4)+2) );
					printf("Getötet mechs:%d\n",*(piErgebnis+(0*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(0*4)+3) );
				
					printf("verloren inf:%d\n", *(piErgebnis+(0*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(1*4)+1));
					printf("verloren fahrzeuge:%d\n",*(piErgebnis+(0*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(1*4)+2) );
					printf("verloren mechs:%d\n",*(piErgebnis+(0*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(1*4)+3) );
				}
				printf("====\n");
				for( iI=0; iI<iAnzahlBauplaeneVerteidiger; iI++ )
				{
					printf("Getötet inf:%d\n", *(piErgebnis+(1*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(0*4)+1));
					printf("Getötet fahrzeuge:%d\n",*(piErgebnis+(1*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(0*4)+2) );
					printf("Getötet mechs:%d\n",*(piErgebnis+(1*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(0*4)+3) );
					
					printf("verloren inf:%d\n", *(piErgebnis+(1*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(1*4)+1));
					printf("verloren fahrzeuge:%d\n",*(piErgebnis+(1*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(1*4)+2) );
					printf("verloren mechs:%d\n",*(piErgebnis+(1*iAnzahlBauplaeneAngreifer*8)+(iI*8)+(1*4)+3) );
					printf("---------\n");
				}*/
		
				/*Alle gestorbenen Einheiten aus Datenbank löschen!*/
				//Angreifer
				for(iI=0; iI<iAnzahlAngreifer; iI++)
				{
					pAngreifer[iI].saveData();
				}
				
				//Verteidiger
				for(iI=0; iI<iAnzahlVerteidiger; iI++)
				{
					pVerteidiger[iI].saveData();
				}
			}
			else	//Keine Verteidiger ODER Angreifer vorhanden!
			{
				//Hat Verteidiger 0 einheiten?
				if( iAnzahlVerteidiger == 0 )
				{
					//Gewinner = Angreifer
					iWinner = 1;
				}
				else
				{
					//Verteidiger = Gewinner
					iWinner = 2;
				}
			}
				
			//Wenn Alle Angreifer Tod, dann die Flotte löschen
			if( iWinner == 2 ) //Verteidiger hat gewonnen
			{
				//Alle Zivilen Einheiten löschen
				sprintf(szQuery, "DELETE FROM t_einheit WHERE ID_Flotte = %d", atoi(dbRow[2]));
				pDB->query(szQuery);
				
				//Truppenverband löschen!
				sprintf(szQuery, "DELETE FROM t_flotte WHERE ID_Flotte = %d", atoi(dbRow[2]));
				pDB->query(szQuery);
				
				//Mission löschen!
				sprintf(szQuery, "DELETE FROM t_mission WHERE ID_Mission = %d", iID_Mission);
				pDB->query(szQuery);						
			}
			//Angreifer hat gewonnen
			else
			{
				//Alle zivilen Einheiten löschen, die keiner Mission zugeordnet sind
				sprintf(szQuery, "SELECT t_einheit.ID_Einheit FROM t_einheit, t_flotte LEFT JOIN t_mission USING(ID_Flotte) WHERE t_einheit.ID_User = %d AND t_einheit.ID_Flotte = t_flotte.ID_Flotte AND ID_Mission is null", atoi(dbRow[5]));
				pDB->query(szQuery);
				while( (dbRow2 = mysql_fetch_row(pDB->dbResult)) )
				{
					//Lösche nun Einheit
					sprintf(szQuery, "DELETE FROM t_einheit WHERE ID_Einheit = %d", atoi(dbRow2[0]));
					pDB2->query(szQuery);
				}
				
				//Alle zivilen Einheiten löschen, die keiner Mission zugeordnet sind
				sprintf(szQuery, "DELETE FROM t_einheit WHERE ID_User = %d AND ID_Flotte = 0", atoi(dbRow[5]));
				pDB->query(szQuery);				
				
				//ALle Truppen löschen, die keine Einheit haben					
				sprintf(szQuery, "SELECT t_flotte.id_Flotte, t_einheit.ID_Einheit FROM t_flotte LEFT JOIN t_einheit USING (ID_Flotte)  WHERE t_flotte.ID_User = %d GROUP BY t_flotte.ID_Flotte", atoi(dbRow[5]));
				pDB->query(szQuery);
				while( (dbRow2 = mysql_fetch_row(pDB->dbResult)) )
				{
					//Wenn ID_Einheit leer ist... flotte löschen!
					if( dbRow2[1] == NULL )
					{
						//Alle Einheiten der Flotte löschen (besonders zivile Einheiten)
						sprintf(szQuery, "DELETE FROM t_einheit WHERE ID_Flotte = %d", atoi(dbRow2[0]));
						pDB2->query(szQuery);
						
						//Flotte löschen
						sprintf(szQuery, "DELETE FROM t_flotte WHERE ID_Flotte = %d", atoi(dbRow2[0]));
						pDB2->query(szQuery);
					}
				}
				
				/*Rohstoffe plündern:)*/
				//Maximale Ladekapazitaet laden
				sprintf(szQuery, "SELECT SUM(MaxZuladung) FROM t_profil, t_einheit WHERE t_profil.ID_Profil = t_einheit.ID_Bauplan AND t_einheit.ID_Flotte = %d AND t_profil.ChassisTyp = 4 GROUP BY t_profil.ChassisTyp", atoi(dbRow[2]));
				//sprintf(szQuery, "SELECT SUM(MaxZuladung), ID_Profil FROM t_profil, t_einheit WHERE t_profil.ID_Profil = t_einheit.ID_Bauplan AND t_einheit.ID_Flotte = %d AND t_profil.ChassisTyp = 4 AND ID_Profil is not null GROUP BY t_profil.ChassisTyp;", atoi(dbRow[2]));
				pDB->query(szQuery);
				dbRow2 = mysql_fetch_row(pDB->dbResult);
								
				//Min 1 Ergebnis vorhanden?
				if( pDB->num_rows() > 0 )
				{
					iLadeKapazitaet = atoi(dbRow2[0]);
				}
				else
				{
					iLadeKapazitaet = 0;
				}
							
							
				/*Bevorzugte Ressourcen laden*/
				/*Ressources is folgendermaßen aufgebaut:
				 * ID|anzahl, ID|anzahl, ID|anzahl*/	 
				pszRessources[0] = strtok(dbRow[6], ",");
				//komma(,) bei Ressourcen ermitteln
				while( pszRessources[0] != NULL )
				{						
					//Lade Anzahl der Rohstoffe
					sprintf(szQuery, "SELECT Anzahl, ID_Rohstoff FROM t_userhatrohstoffe WHERE ID_Rohstoff = %d AND ID_Kolonie = %d AND ID_User = %d", atoi(pszRessources[0]), iID_Kolonie2, atoi(dbRow[5]));
					pDB->query(szQuery);
					dbRow2 = mysql_fetch_row(pDB->dbResult);
					
					
					//iLadeKapazitaet überprüfen
					if( atoi(dbRow2[0]) > iLadeKapazitaet )
					{
						iRohstoffAnzahl = iLadeKapazitaet;
					}
					else
					{
						iRohstoffAnzahl = atoi(dbRow2[0]);
					}
											
					//Rohstoffe in Datenbank aktuallisieren
					sprintf(szQuery, "UPDATE t_userhatrohstoffe SET Anzahl = (Anzahl - %d) WHERE ID_User = %d AND ID_Rohstoff = %d AND ID_Kolonie = %d", iRohstoffAnzahl, atoi(dbRow[5]), atoi(dbRow2[1]), iID_Kolonie2);
					pDB->query(szQuery);
					
					//RohstoffString setzen!
					sprintf(szRohstoffe, "%s%d|%d,", szRohstoffe, atoi(dbRow2[1]), iRohstoffAnzahl);
					//LadeKapazitaet verringern
					iLadeKapazitaet -= iRohstoffAnzahl;
					
					//Setze geplünderte Rohstoffe für den Kampfbericht
					iKampfberichtRohstoffe[atoi(dbRow2[1])] = iRohstoffAnzahl;
					
					//Nach nächstem Rohstoff suchen					
					pszRessources[0] = strtok(NULL, ",");
				}			
				
				//Alle Rohstoffe, welche gewünscht waren wurden nun geladen. Nun weitere Rohstoffe solange laden, bis Flotte voll :)
				do
				{									
					//zufälligen Rohstoff bestimmen
					iZufall = irand(1, 14);
					
					//Lade Anzahl der Rohstoffe
					sprintf(szQuery, "SELECT Anzahl, ID_Rohstoff FROM t_userhatrohstoffe WHERE ID_Kolonie = %d AND ID_User = %d ORDER BY ID_Rohstoff ASC Limit %d, 1", iID_Kolonie2, atoi(dbRow[5]), (iZufall-1));
					pDB->query(szQuery);
					dbRow2 = mysql_fetch_row(pDB->dbResult);
										
					//iLadeKapazitaet überprüfen
					if( atoi(dbRow2[0]) > iLadeKapazitaet )
					{
						iRohstoffAnzahl = iLadeKapazitaet;
					}
					else
					{
						iRohstoffAnzahl = atoi(dbRow2[0]);
					}
					
					//Rohstoffe in Datenbank aktuallisieren
					sprintf(szQuery, "UPDATE t_userhatrohstoffe SET Anzahl = (Anzahl - %d) WHERE ID_User = %d AND ID_Rohstoff = %d AND ID_Kolonie = %d", iRohstoffAnzahl, atoi(dbRow[5]), atoi(dbRow2[1]), iID_Kolonie2);
					pDB->query(szQuery);
					
					//RohstoffString setzen!
					sprintf(szRohstoffe, "%s%d|%d,", szRohstoffe, atoi(dbRow2[1]), iRohstoffAnzahl);
					//LadeKapazitaet verringern
					iLadeKapazitaet -= iRohstoffAnzahl;
					
					//Noch verfügbare Rohstoffe des Users laden
					sprintf(szQuery, "SELECT SUM(FLOOR(Anzahl)) FROM t_userhatrohstoffe WHERE ID_User = %d AND ID_Kolonie = %d", atoi(dbRow[5]), iID_Kolonie2);
					pDB->query(szQuery);
					dbRow2 = mysql_fetch_row(pDB->dbResult);
					
					//Setze geplünderte Rohstoffe für den Kampfbericht
					iKampfberichtRohstoffe[atoi(dbRow2[1])] = iRohstoffAnzahl;
					
					//Noch verfügbare Rohstoffe setzen
					iRohstoffeLeft = atoi(dbRow2[0]);
				}while(iLadeKapazitaet > 0 && iRohstoffeLeft > 0);
				
				//Setze Ressourcen Spalte in Tabelle Mission neu
				sprintf(szQuery, "UPDATE t_mission SET Ressources = '%s' WHERE ID_Mission = %d", szRohstoffe, iID_Mission);
				pDB->query(szQuery);
			}
			
			//Kampf auswerten!
			speichereBericht(atoi(dbRow[4]), atoi(dbRow[5]), iWinner,  piErgebnis, pKampf, iAnzahlBauplaeneAngreifer, iAnzahlBauplaeneVerteidiger, piAnzahlBauplanEinheiten, piBauplanUebersetzung, pDB, piKampfberichtID, piKampfberichtID2, atoi(dbRow[8]), &iKampfberichtRohstoffe[0]);
						
			//Topic setzen
			sprintf(szTopic, "Kampfbericht");	
			//AngreiferNachricht
			sprintf(szInhalt, "Ein Kampf hat stattgefunden<br><a href=\"bericht.php?ID=%d\" target=\"_blank\">zum Kampfbericht</a>", *piKampfberichtID);
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[4]), iID_Kolonie);
			pDB->query(szQuery);
			//VerteidigerNachricht
			sprintf(szInhalt, "Ein Kampf hat stattgefunden<br><a href=\"bericht.php?ID=%d\" target=\"_blank\">zum Kampfbericht</a>", *piKampfberichtID2);
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[5]), iID_Kolonie2);
			pDB->query(szQuery);
			
			//Speicher freigeben
			delete pVerteidiger;
			delete pAngreifer;
			delete pKampf;
			delete pDB2;
		}		
		/*spionieren*/
		else if( 0 == strcmp( dbRow[1], "Spionage") )
		{
		}
		/*Rohstoffe transportieren*/
		else if( 0 == strcmp( dbRow[1], "Transport") )
		{
			/*Ressources is folgendermaßen aufgebaut:
			 * ID|anzahl, ID|anzahl, ID|anzahl*/		 
			pszRessources[0] = strtok(dbRow[6], ",");
			iI=1;
			//komma(,) bei Ressourcen ermitteln
			while( iI != 14 )
			{								
				pszRessources[iI] = strtok(NULL, ",");			
				iI++;
			}			
			
			//ID und anzahl trennen
			for(iA=0; iA<iI; iA++)
			{
				//ID und Anzahl speichern
				pszRessourcesDetail[0] = strtok(pszRessources[iA], "|");
				pszRessourcesDetail[1] = strtok(NULL, "|");
				
				//Wenn Anzahl != 0, dann rohstoffe gutschreiben
				if( atof(pszRessourcesDetail[1]) != 0 )
				{
					//Rohstoffe gutschreiben
					sprintf(szQuery, "UPDATE t_userhatrohstoffe SET Anzahl = (Anzahl + %lf) WHERE ID_User = %d AND ID_Rohstoff = %d", atof(pszRessourcesDetail[1]), atoi(dbRow[5]), atoi(pszRessourcesDetail[0]));
					pDB->query(szQuery);
				}
			}
			//Topic setzen
			sprintf(szTopic, "Rohstofftransport");	
			//AngreiferNachricht
			sprintf(szInhalt, "Es wurden Rohstoffe transportiert");
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[4]), iID_Kolonie);
			pDB->query(szQuery);
			//VerteidigerNachricht
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[5]), iID_Kolonie2);
			pDB->query(szQuery);
			
			//Topic setzen
			sprintf(szTopic, "Rohstofftransport");		
		}
		/*Verteidigen*/
		else if( 0 == strcmp( dbRow[1], "Verteidigung") )
		{
		}
		/*Truppe übergeben*/
		else if( 0 ==  strcmp( dbRow[1], "Uebergabe") )
		{			
			//Ändere ID_Kolonie von Einheiten			
			sprintf(szQuery, "UPDATE t_einheit SET ID_Kolonie = %d WHERE ID_Flotte = %d", iID_Kolonie2, atoi(dbRow[2]));
			pDB->query(szQuery);
			
			//Ändere ID_User von Einheiten
			sprintf(szQuery, "UPDATE t_einheit SET ID_User = %d WHERE ID_Flotte = %d", atoi(dbRow[5]), atoi(dbRow[2]));
			pDB->query(szQuery);
			
			//Setze ID_Flotte in Einheiten tabelle auf 0
			sprintf(szQuery, "UPDATE t_einheit SET ID_Flotte = 0 WHERE ID_Flotte = %d", atoi(dbRow[2]));
			pDB->query(szQuery);
			
			//Lösche Flotte!
			sprintf(szQuery, "DELETE FROM t_flotte WHERE ID_Flotte = %d", atoi(dbRow[2]));
			pDB->query(szQuery);
			
			//'Lösche mission, da Einheiten nicht zurückfliegen können!
			sprintf(szQuery, "DELETE FROM t_mission WHERE ID_Mission = %d", iID_Mission);
			pDB->query(szQuery);
			
			//Topic setzen
			sprintf(szTopic, "Truppe übergeben");	
			sprintf(szInhalt, "Es wurden eine Truppe übergeben.");
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[4]), iID_Kolonie);
			pDB->query(szQuery);
			//VerteidigerNachricht
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[5]), iID_Kolonie2);
			pDB->query(szQuery);
		}
		/*Truppe verlegen*/
		else if( dbRow[1] == "Verlegen" )
		{			
			//Ändere ID_Kolonie von Einheiten			
			sprintf(szQuery, "UPDATE t_einheit SET ID_Kolonie = %d WHERE ID_Flotte = %d", iID_Kolonie2, atoi(dbRow[2]));
			pDB->query(szQuery);
											
			//Lösche mission, da Einheiten nicht zurückfliegen können!
			sprintf(szQuery, "DELETE FROM t_mission WHERE ID_Mission = %d", iID_Mission);
			pDB->query(szQuery);
			
			//Topic setzen
			sprintf(szTopic, "Truppe verlegt");	
			
			sprintf(szInhalt, "Eine Ihrer Truppen wurde erfolgreich verlegt.");
			sprintf(szQuery, "INSERT INTO t_ereignis (Titel, Betreff, Datum, Status, ID_User, ID_Kolonie) VALUES ('%s', '%s', %d, 'neu', %d, %d)", szTopic, szInhalt, atoi(dbRow[8]),atoi(dbRow[4]), iID_Kolonie);
			pDB->query(szQuery);
		}
		/*koloniesieren*/
		else if( 0 == strcmp( dbRow[1], "Kolonisieren") )
		{
		}
		
		//Setze Ausgeführt status auf 1!
		sprintf(szQuery, "UPDATE t_mission SET Ausgefuehrt = 1 WHERE ID_Mission = %d", iID_Mission);
		pDB->query(szQuery);
				
		//Variablen löschen und speicher reservieren
		delete pDB;
	}
	else	//Zu wenig Übergabeparameter
	{
		iError = -1;
	}
		
	//Rückgabewert
	return iError;
}

