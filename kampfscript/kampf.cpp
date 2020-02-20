/*kampf.cpp
Dieses Script stellt die Schnittstelle des Kampfes bereit.
Sie bekommt $angreifer_array und $verteidiger-array übergeben, welche
alle Einheiten dieser beinhalten. 
Der Kampf wird folgendermaßen abgewickelt
	1. Der Angreifer schießt zuerst.
	2. Das Bonuschasssis wird zuerst als Ziel gewählt.
	 --> Kein Bonuschassis vorhanden. Bonuswaffe wählen.
	 --> Keine Bonuswaffe vorhanden --> Eigenen Typ.
	 --> Keine eigenen Einheiten --> letzten Typ bekämpfen.
	3. Aus dem gewählten Zieltyp wird zufällig eine Zieleinheit gewählt und beschossen.
	4. Es wird berechnet, ob der Angreifer trifft. Dazu wird die Trefferwahrscheinlichkeit mit folgender
	   Formel berechnet: (Zielwert - Manöverwert)/60 + 0.5
	5. Trifft der Angreifer, so wird folgendes berechnet:
	   1. Der Angriffswert wird aus den Boni von Chassis und Waffe des Angreifers unter Berücksichtigung des Zieltyps errechnet.
	   2. Nun wird der Verteidigungswert der beschossenen Einheit aus dem Panzerungsbonus und dem Waffentyp ermittelt.
	   3. Der Verteidigungswert wird noch vom Angriffswert abgezogen, wobei der resultierende Schaden nie negativ oder
	      Null ist, sondern mindestens 1 beträgt.
	6. Es feuern alle weiteren angreifenden Einheiten, bis entweder der Verteidiger vernichtet ist, oder alle Angreifer einmal
	   gefeuert haben. 
	7. Falls noch Verteidiger überlebt haben, so dürfen nun diese feuern. Hat der Angreifer die Fluchparameter seiner Einheiten
	   entsprechend eingestellt, so ziehen sich diese bei zu starkem Schaden zurück, wenn der Verteidiger seine Aktionen 
	   beendet hat.
	8. Dies wird solange wiederholt, bis entweder alle Verteidiger vernichtet wurden, oder alle Angreifer geflohen sind bzw.
	   getötet wurden.
	 
	   
History:
				07.09.2004		Markus von Rüden		created
*/

#include "kampf.h"

/*Standardkonstruktor leitet Kampf ein!
	$angreifer_array und $verteidiger_array sind objekte vom Typ Einheiten und folgendermaßen aufgebuat:
	$angreifer_array[$i] = EINHEITEN-OBJEKT selbiges gilt für $verteidiger_array*/
KAMPF::KAMPF(
	EINHEIT *ptAngreifer, 
	EINHEIT *ptVerteidiger, 
	int iAnzahl_Angreifer, 
	int iAnzahl_Verteidiger, 
	int *iAnzahl_Typen_Angreifer, 
	int *iAnzahl_Typen_Verteidiger,
	int iArtilleriebeschuss,		/*Soll Artilleriebeschuss ausgeführt werden?*/
	int iAnzahlBauplaeneAngreifer, 
	int iAnzahlBauplaeneVerteidiger, 
	int *piErgebnis, 
	int *piBauplanUebersetzung)
{	
	//Werte auf 0 setzen
	this->iAnzahl_Typen[0][0] = 0;
	this->iAnzahl_Typen[0][1] = 0;
	this->iAnzahl_Typen[0][2] = 0;
	this->iAnzahl_Typen[1][0] = 0;
	this->iAnzahl_Typen[1][1] = 0;
	this->iAnzahl_Typen[1][2] = 0;
	this->iAnzahl_Typen[2][0] = 0;
	this->iAnzahl_Typen[2][1] = 0;
	this->iAnzahl_Typen[2][2] = 0;
	this->iAnzahl_Typen[3][0] = 0;
	this->iAnzahl_Typen[3][1] = 0;
	this->iAnzahl_Typen[3][2] = 0;
	this->iAnzahlLebend_Typen[0][0] = 0;
	this->iAnzahlLebend_Typen[0][1] = 0;
	this->iAnzahlLebend_Typen[0][2] = 0;
	this->iAnzahlLebend_Typen[1][0] = 0;
	this->iAnzahlLebend_Typen[1][1] = 0;
	this->iAnzahlLebend_Typen[1][2] = 0;
	this->iAnzahlLebend_Typen[2][0] = 0;
	this->iAnzahlLebend_Typen[2][1] = 0;
	this->iAnzahlLebend_Typen[2][2] = 0;
	this->iAnzahlLebend_Typen[3][0] = 0;
	this->iAnzahlLebend_Typen[3][1] = 0;
	this->iAnzahlLebend_Typen[3][2] = 0;
	
	//Anzahl auf 0 setzen
	this->iAnzahl[0] = 0;
	this->iAnzahl[1] = 0;
	this->iAnzahl[2] = 0;
	this->iAnzahlLebend[0] = 0;
	this->iAnzahlLebend[1] = 0;
	this->iAnzahlLebend[2] = 0;
	
	//Zeiger auf Einheitenobjekte setzen!
	this->pEinheiten[0] = 0;
	this->pEinheiten[1] = ptAngreifer;
	this->pEinheiten[2] = ptVerteidiger;

	//Anzahl setzen
	this->iAnzahl[1] 		= iAnzahl_Angreifer;
	this->iAnzahl[2] 		= iAnzahl_Verteidiger;
	this->iAnzahlLebend[1] = this->iAnzahl[1];
	this->iAnzahlLebend[2] = this->iAnzahl[2];
	
	//Anzahl Infanteristen setzen
	this->iAnzahl_Typen[1][1] 		= iAnzahl_Typen_Angreifer[1];
	this->iAnzahl_Typen[1][2] 		= iAnzahl_Typen_Verteidiger[1];
	this->iAnzahlLebend_Typen[1][1] = this->iAnzahl_Typen[1][1];
	this->iAnzahlLebend_Typen[1][2] = this->iAnzahl_Typen[1][2];
	
	//Anzah Fahrzeuge setzen
	this->iAnzahl_Typen[2][1] 		= iAnzahl_Typen_Angreifer[2];
	this->iAnzahl_Typen[2][2] 		= iAnzahl_Typen_Verteidiger[2];
	this->iAnzahlLebend_Typen[2][1] = this->iAnzahl_Typen[2][1];
	this->iAnzahlLebend_Typen[2][2] = this->iAnzahl_Typen[2][2];
	
	//Anzahl Mechs setzen
	this->iAnzahl_Typen[3][1]		= iAnzahl_Typen_Angreifer[3];
	this->iAnzahl_Typen[3][2] 		= iAnzahl_Typen_Verteidiger[3];
	this->iAnzahlLebend_Typen[3][1] = this->iAnzahl_Typen[3][1];
	this->iAnzahlLebend_Typen[3][2] = this->iAnzahl_Typen[3][2];
	
	/*Variabeln für Detaiillierte Ausgabe erstellen*/
	//Anzahl Baupläne
	this->iAnzahlBauplaene[0] = iAnzahlBauplaeneAngreifer;
	this->iAnzahlBauplaene[1] = iAnzahlBauplaeneVerteidiger;
	
	//Bauplanübersetzungne
	this->piBauplanUebersetzung[0] 	= piBauplanUebersetzung;					
	this->piBauplanUebersetzung[1] 	= (piBauplanUebersetzung+iAnzahlBauplaeneAngreifer);
	
	//Ergebnis setzen
	this->piErgebnis				= piErgebnis;
}
    
/*Funktion führt Kampf aus.
iFirstAttack gibt an ob Verteidiger als 1. schiesst, oder der Angreifer
1 = angreifer
2 = verteidiger*/
int KAMPF::starteKampf(int iFirstAttack)
{
	//Deklarationen
	int iFinish 		= 1;	//KEine Fehler aufgetreten
	int iLastAttack 	= 0;	//Wer kämpft als 2.?
	int iI 				= 0;	//Zählvariabel
	int iA				= 0;
	int iChassisTyp 	= 0; 	//Eigener Chassistyp
	int iBonusChassisTyp= 0;	//Bonuschassis
	double dBonusChassis 	= 0;	//Größe des Bonus
	int iBonusWaffenTyp = 0;	//Waffenbonustyp
	double dBonusWaffen 	= 0;	//Wie hoch ist der Bonus?
	int iAngreiferTyp 	= 0;	//Welcher Typ soll standardmäßig angegriffen werden?
	int iFeindNummer 	= 0;	//Welcher Feind soll attackiert werden?
	int iOffset			= 0;	//Je nach anzugreifenden Typ muss ein bestimmter Offset angegeben werden!
	int iID_Bauplan		= 0;	//Muss für interne Zwecke benutzt werden um eine detaillierte Ergebnsansicht zu erzeugen!
	double dZielgenauigkeit= 0;	//Wie hoch ist die Zielgenauigkeit?
	double dZielwahrscheinlichkeit 	= 0; //Wie hoch ist die Wahrscheinlichkeit, dass getroffen wird?
	double dVerteidigungsBonus 		= 0;
	double dAngriffswert 			= 0;
	double dVerteidigungswert 		= 0;
	double dAngriffsBonus 			= 0;
	double dSchaden 				= 0;
	double dSchadenFaktor			= 0;
	
	
	//Last Attack definieren
	if( iFirstAttack == 1 )
	{
		iLastAttack = 2;
	}
	else
	{
		iLastAttack = 1;
	}

	//Debugmeldungen
	//sprintf(this->szDebugString, "\n\nEs schiesst %d als erster!\n", iFirstAttack);
	
	/*Je nachdem, was zu erst schiesst, Verteidiger oder Angreifer druchlaufen*/
	for( iI=0; iI<this->iAnzahl[iFirstAttack]; iI++ )
	{
		//Lebt Einheit noch?		
		if( this->pEinheiten[iFirstAttack][iI].getLebenspunkte() > 0 )
		{
			iChassisTyp 		= this->pEinheiten[iFirstAttack][iI].getChassis();
			//BonusChassis wählen
			iBonusChassisTyp 	= this->pEinheiten[iFirstAttack][iI].getChassisBonusTyp();
			dBonusChassis		= this->pEinheiten[iFirstAttack][iI].getChassisBonus();
			//Bonuswaffe wählen
			iBonusWaffenTyp		= this->pEinheiten[iFirstAttack][iI].getWaffenBonusTyp();
			dBonusWaffen		= this->pEinheiten[iFirstAttack][iI].getWaffenBonus();

			//Ist BonusWaffenTyp vorhanden?
			if( this->iAnzahlLebend_Typen[iBonusWaffenTyp][iLastAttack] > 0 ) 
			{
				//WaffenBonusTyp vorhanden
				iAngreiferTyp = iBonusWaffenTyp;
			}
			//Kein BonusWaffenTyp vorhanden. Ist Ein BonusChassisTyp vorhanden?
			else if( this->iAnzahlLebend_Typen[iBonusChassisTyp][iLastAttack] > 0 )
			{
            	//Bonuschassistyp vorhanden
                iAngreiferTyp = iBonusChassisTyp;
			}
			else	//Kein Bonus-WaffenTyp, ChassisTyp oder eigener Typ vorhanden letzten Typ bekämpfen
			{
				//Durchlaufe alle vorhandenen AnzahlTypen um angreifertyp zu bestimmen
				for( iA=1; iA<4; iA++ )
				{
					//Ist AnzahlTypen != null?
					if( this->iAnzahlLebend_Typen[iA][iLastAttack] > 0 )
					{
						iAngreiferTyp = iA;
						break;
					}
				}
            }  	    
			  
			/*Wenn BonusWaffenTyp nicht für Infanteristen ist (!= 1), und ChassisTyp vom AngreiferTyp ein Infanterist ist
			dann muss der Schaden um 80% abgeschwächt werden*/
			if( iAngreiferTyp == 1 && iBonusWaffenTyp != 1 )
			{
				//Aufgrund Balancinggründen muss der Schaden um 90% gesenkt werden
				dSchadenFaktor = 0.15;	
			}
			else
			{
				//Schaden bleibt gleich
				dSchadenFaktor = 1;
			}
			
			//Offset bestimmten
			iOffset = 0;			//Offset resetten!
			for(iA=1; iA<(iAngreiferTyp); iA++)
			{
				iOffset += this->iAnzahl_Typen[iA][iLastAttack];
			}
			     
            /*iAngreiferTyp ist nun definiert und gibt an welchen ChassisTyp 
             * die Einheit angreift!
             * Nun eine Einheit vom AngreiferTyp zufällig wählen!*/
            do
	        {
	        	// Zufallsgenerator mit Systemzeit initialisieren. Zufallszahl zw. 0 und MaxAnzahlEinheiten vom Typ 1,2 oder 3
				iFeindNummer = irand(0,(this->iAnzahl_Typen[iAngreiferTyp][iLastAttack]-1));
				
	        }while( this->pEinheiten[iLastAttack][iFeindNummer+iOffset].getLebenspunkte() <= 0 );
                        
            //iFeindNummer definiert! nun Offset regulär draufaddieren!
            iFeindNummer += iOffset;
            
            //Zielgenauigkeit ermitteln
            dZielgenauigkeit = ((this->pEinheiten[iFirstAttack][iI].getZielen() - this->pEinheiten[iLastAttack][iFeindNummer].getWendigkeit()) / 60) + 0.5;
           	
            //Zufallszahl ermitteln um zu schauen ob getroffen wurde!
            dZielwahrscheinlichkeit = irand(0,100);			//Zufall von 1 - 100
                        	            
            //Liegt Zielwahrscheinlichkeit im Bereich von 
            //dZielgenauigkeit * 100?
            if( dZielwahrscheinlichkeit <= (dZielgenauigkeit * 100) )
            {
                /*Einheit trifft Boni berechnen!*/
                //Angreiferboni
                if( iBonusChassisTyp == this->pEinheiten[iLastAttack][iFeindNummer].getChassis() )
                {
                	dAngriffsBonus += dBonusChassis;
                }
                if( iBonusWaffenTyp == this->pEinheiten[iLastAttack][iFeindNummer].getChassis() )
                {
                	dAngriffsBonus += dBonusWaffen;
                }
	                
	               
				//Verteidigerboni!
	            if( this->pEinheiten[iFirstAttack][iI].getWaffenTyp() == this->pEinheiten[iLastAttack][iFeindNummer].getPanzerTyp() )
	            {
	            	dVerteidigungsBonus += this->pEinheiten[iLastAttack][iFeindNummer].getPanzerBonus();
	            }
	                
				//Angriffswerte und Verteidigugnswerte setzen!
				dAngriffswert = this->pEinheiten[iFirstAttack][iI].getAngriff() * (1+dAngriffsBonus);
				dVerteidigungswert = this->pEinheiten[iLastAttack][iFeindNummer].getPanzerung() * (1+dVerteidigungsBonus);

                //Angerichteter Schaden?
                dSchaden = dAngriffswert - dVerteidigungswert;
                                
                //Wenn dSchaden <= 0, dann Schadne =1
                if( dSchaden <= 0 )
                {
                	dSchaden = 1;
                }
                
                //Schaden mit Faktor einbeziehen
                dSchaden = dSchaden * dSchadenFaktor;
                
                //Schaden abziehen!
                this->pEinheiten[iLastAttack][iFeindNummer].addLebenspunkte((-1)*dSchaden);
                             
                //Lebt Einheit noch?
                if( this->pEinheiten[iLastAttack][iFeindNummer].getLebenspunkte() <= 0 )
                {
                	//Einheit Tod!
                	this->pEinheiten[iLastAttack][iFeindNummer].addLebenspunkte( (-1) * this->pEinheiten[iLastAttack][iFeindNummer].getLebenspunkte() );
                	
                	//Einheiten-Anzahl verringern (rein imaginär)
                	this->iAnzahlLebend_Typen[iAngreiferTyp][iLastAttack]--;
                	this->iAnzahlLebend[iLastAttack]--;
                	
					//BauplanId holen
                	iID_Bauplan = this->getBauplanID(this->pEinheiten[iFirstAttack][iI].getID(), iFirstAttack-1);
                	//AngreiferDetails setzen!
                	//this->piErgebnis[iFirstAttack-1][iID_Bauplan][0][this->pEinheiten[iLastAttack][iFeindNummer].getChassis()]++;
                	*(piErgebnis+((iFirstAttack-1)*(this->iAnzahlBauplaene[0])*2*4)+(iID_Bauplan*8)+(0*4)+(this->pEinheiten[iLastAttack][iFeindNummer].getChassis()))+=1;
					//iID_Bauplan = *(piErgebnis+((iFirstAttack-1)*(this->iAnzahlBauplaene[0])*2*4)+(iID_Bauplan*8)+(0*4)+(this->pEinheiten[iLastAttack][iFeindNummer].getChassis()));
                	               	
                	//BauplanId holen
                	iID_Bauplan = this->getBauplanID(this->pEinheiten[iLastAttack][iFeindNummer].getID(), iLastAttack-1);
                	//VerteidigerDetails setzen
                	*(this->piErgebnis+((iLastAttack-1)*(this->iAnzahlBauplaene[0])*2*4)+(iID_Bauplan*8)+(1*4)+(this->pEinheiten[iFirstAttack][iI].getChassis()))+=1;
					//this->piErgebnis[iLastAttack-1][iID_Bauplan][1][this->pEinheiten[iFirstAttack][iI].getChassis()]++;
                }
                
                //Sind noch Gegner zum kämpfen da?
                if( this->iAnzahlLebend[iLastAttack] == 0 )	//nein
                {
                    //Schleife verlassen
                   	iFinish = -1;	//Alle Einheiten Tod!
                    break;
                }
            }
            else 
            {
                //Einheit trifft nicht!
                dAngriffswert = 0;
                dVerteidigungswert = 0;
                dSchaden = 0;
            }
        }
    }
    return iFinish;
}

/*Funktion sucht BauplanID in array!
 * iAttacker gibt an, ob Verteidiger oder Angreifer geeint is
 * iATtacker = 0 => angreifer
 * iAttacker = 1 => verteidiger*/
int KAMPF::getBauplanID(int iID, int iAttacker)
{
	//Deklarationen
	int iI = 0;	//Zählvariabel
	int iReturn = 0;
	
	//Durchlaufe uebersetzer array
	for(iI=0; iI<this->iAnzahlBauplaene[iAttacker]; iI++)
	{
		if( this->piBauplanUebersetzung[iAttacker][iI] == iID )
		{
			iReturn = iI;
			break;
		}
	}
	
	//Rückgabewert
	return iReturn;
}

int KAMPF::getAnzahlTypAngreiferLebend(int iID_Typ)
{
	return this->iAnzahlLebend_Typen[iID_Typ][1];
}

int KAMPF::getAnzahlTypVerteidigerLebend(int iID_Typ)
{
	return this->iAnzahlLebend_Typen[iID_Typ][2];
}

int KAMPF::getAnzahlTypAngreifer(int iID_Typ)
{
	return this->iAnzahl_Typen[iID_Typ][1];
}

int KAMPF::getAnzahlTypVerteidiger(int iID_Typ)
{
	return this->iAnzahl_Typen[iID_Typ][2];
}

int KAMPF::getAnzahlAngreifer(void)
{
	return this->iAnzahl[1];
}

int KAMPF::getAnzahlVerteidiger(void)
{
	return this->iAnzahl[2];
}

int KAMPF::getAnzahlAngreiferLebend(void)
{
	return this->iAnzahlLebend[1];
}

int KAMPF::getAnzahlVerteidigerLebend(void)
{
	return this->iAnzahlLebend[2];
}
