#ifndef KAMPFBERICHT_
#define KAMFBERICHT_

//Includes
#include <iostream>
#include <fstream>
#include <stdio.h>
#include <mysql/mysql.h>
#include "db.h"
#include "kampf.h"
#include <string.h>
#include "zufall.h"
#include "define.h"


using namespace std;

//Funktionsdeklarationen
int getProzentWert(int iMax, int iWert);
void speichereBericht(int iID_Angreifer,int iID_Verteidiger, int iID_Winner, int *piErgebnis, KAMPF *pKampf, int iBauplanAngreifer, int iBauplanVerteidiger, int *piAnzahlBauplaeneEinheiten, int *piBauplanArray, DATENBANK *pDB, int *piOutputDatei, int *piOutputDatei2, int iDatumDesKampfes, int *piRohstoffDetails);
string setzeKampfDetailBereich(int *piErgebnis, int *piBauplanArray, int *piAnzahlBauplanEinheiten, int iAnzahlBauplaene, DATENBANK *pDB, string sdetails, int iMax);

#endif
