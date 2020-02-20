#ifndef EINHEITEN_
#define EINHEITEN_
/*einheiten.h
Diese Klasse ist von BAUPLAN abgeleitet
und repräsentiert die eigentliche Einheit!*/

#include "bauplan.h"
#include <math.h>
#include <stdio.h>

class EINHEIT : public BAUPLAN
{
	//Objektvariablen
	private:
		int iID_Einheit;
		int iErfahrung;
		double dLebenProzent;
		double dHP;		
	
	//Klassenmethoden
	public:
		EINHEIT(int iID_Einheit, int iID_User, int iID_Bauplan, DATENBANK *pDB);
		~EINHEIT();
		void addLebenspunkte(double dADD);
		double getLebenspunkte(void);
		void saveData(void);
	private:
		void delEinheit(void);
		void setLebensprozent(void);
		
};
#endif
