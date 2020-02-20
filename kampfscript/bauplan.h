#ifndef BAUPLAN_
#define BAUPLAN_
/*bauplan.h
stellt Headerfile zum Bauplan zur Verfügung.
anhand eines Bauplanes werden Einheiten erstellt


History:
		07.09.2004		mvr		created
*/

#include "db.h"
#include <stdlib.h>
#include <stdio.h>

class BAUPLAN
{
	//Objektvariablen
	protected:
		DATENBANK *pDB;
		int iID_User;
		int iID;
		int iAngriff;
		int iPanzerung;
		int iLebenspunkte;
		int iWendigkeit;
		int iZielen;
		int iWaffenTyp;
		int iBonus_WaffenTyp;
		double dBonus_Waffe;
		int iChassisTyp;
		int iBonus_ChassisTyp;;
		double dBonus_Chassis;
		int iPanzerTyp;
		double dBonus_Panzer;
	
	//Funktionen
	public:
		BAUPLAN();
		~BAUPLAN();
		int getID(void);
		void setzeDatenbank(DATENBANK *pDB);
		void loadBauplan(int ID_User, int iID_Profil);
		int getAngriff(void);
		int getChassis(void);
		int getChassisBonusTyp(void);
		double getChassisBonus(void);
		int getPanzerung(void);
		double getPanzerBonus(void);
		int getPanzerTyp(void);
		int getWendigkeit(void);
		int getZielen(void);
		int getWaffenTyp(void);
		int getWaffenBonusTyp(void);
		double getWaffenBonus(void);
};
#endif
