#ifndef KAMPF_
#define KAMPF_
/*kampf.h
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
#include "einheiten.h"
#include <stdio.h>
#include <stdlib.h>
#include "zufall.h"

class KAMPF
{
	private:
		EINHEIT *pEinheiten[3]; /*0=>undefined 1=> angreifer 2=> verteidiger*/
		int iAnzahl[3];			/*Anzahl für Angreifende Einheiten und verteidigende Einheiten
								0 => undefined
								1 => Anzahl Angreifer
								2 => Anzahl Verteidiger*/
		int iAnzahlLebend[3];	//Speichert selbe Werte wie iAnzahl, nur der Wert kann sich verändern, wenn z.b. eine Einheit stirbt
		int iAnzahl_Typen[4][3];/* 	iAnzahlTypen[0] = leer
									iAnzahlTypen[1] Infanteristen!									
									iAnzahlTypen[2] Fahrzeuge!
									iAnzahlTypen[3] Mechs!
									
									iAnzahlTypen[x][0] => undefined
									iAnzahlTypen[x][1] => Anzahl Angreifer
									iAnzahlTypen[x][1] => ANzahl Verteidiger*/
		int iAnzahlLebend_Typen[4][3];	//Speichert selbe Werte wie iAnzahl_Typen, aber auch hier können sich die Werte verändern!
		int iArtillerie;		/*1 = ja, 0 = nein*/
		int iAnzahlBauplaene[2];	//Anzahl der Baplane [0] => Angreifer [1] => Verteidiger
		int *piBauplanUebersetzung[2];	//Zeiger auf BauplanUebersetzungen!
		int *piErgebnis;	//Zeiger auf Ergebnis!
	
	//Funktionen
	private:
		int getBauplanID(int iID, int iAttacker);
	public:
		KAMPF(EINHEIT *ptAngreifer, EINHEIT *ptVerteidiger, int iAnzahl_Angreifer, int iAnzahl_Verteidiger, int *iAnzahl_Typen_Angreifer, int *iAnzahl_Typen_Verteidiger, int iArtilleriebeschuss, int iAnzahlBauplaeneAngreifer, int iAnzahlBauplaeneVerteidiger, int *piErgebnis, int *piBauplanUebersetzung);
		int starteKampf(int iFirstAttack);
		int getAnzahlTypAngreiferLebend(int iID_Typ);
		int getAnzahlTypVerteidigerLebend(int iID_Typ);
		int getAnzahlAngreifer(void);
		int getAnzahlVerteidiger(void);
		int getAnzahlTypAngreifer(int iID_Typ);
		int getAnzahlTypVerteidiger(int iID_Typ);
		int getAnzahlAngreiferLebend(void);
		int getAnzahlVerteidigerLebend(void);
};
#endif
