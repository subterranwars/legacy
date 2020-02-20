/*einheiten.cpp
 * 
 * History:
 * 				07.09.2004		MvR		created
 *
 * */
 
#include "einheiten.h"

EINHEIT::EINHEIT(int iID_Einheit, int iID_User, int iID_Bauplan, DATENBANK *pDB)
{
	//Deklarationen
	char szQuery[500];		//QueryString
	MYSQL_ROW dbRow;		//ErgebnisVariable
		
	//Datenbankverbindung setzen
	this->setzeDatenbank(pDB);
	
	//Einheitendaten laden
	this->loadBauplan(iID_User, iID_Bauplan);
	this->iID_Einheit = iID_Einheit;
	
	//Restliche Daten laden
	sprintf(szQuery, "SELECT Erfahrung, LebenProzent FROM t_einheit WHERE ID_Einheit = %d;", iID_Einheit);
	this->pDB->query(szQuery);
	
	//Ergebnis laden!
	dbRow = mysql_fetch_row(this->pDB->dbResult);	
	this->iErfahrung 	= atoi(dbRow[0]);
	this->dLebenProzent = atof(dbRow[1]);
	
	//Setze HP der Einheit
	this->dHP = this->iLebenspunkte * this->dLebenProzent;
	
	//Aufräumen
	this->pDB->free_result();
}
 
EINHEIT::~EINHEIT()
{
 	
}
 
void  EINHEIT::addLebenspunkte(double dADD)
{
	//Wenn mehr Lebenspunkte hinzugefügt werden als 100%, dann ist hp = 100%
	if( (this->dHP + dADD) > this->iLebenspunkte )
	{
		this->dHP = this->iLebenspunkte;
		this->dLebenProzent = 1;		//100%!
	}
	else
	{
		this->dHP += dADD;
		this->dLebenProzent = (100 / (double)this->iLebenspunkte) * this->dHP / 100;
	}
}

double EINHEIT::getLebenspunkte(void)
{
	return this->dHP;
}

void EINHEIT::delEinheit(void)
{
	char szQuery[250];
	
	sprintf(szQuery, "DELETE FROM t_einheit WHERE ID_Einheit = %d", this->iID_Einheit);
	this->pDB->query(szQuery);
}

void EINHEIT::saveData(void)
{
	//Wenn Einheit = tod, dann löschen
	if( this->dHP <= 0 || ceil(this->dLebenProzent * 100) <= 0  )
	{
		this->delEinheit();
	}
	//Sonst lebenprozent setzen!
	else
	{
		this->setLebensprozent();
	}
}

void EINHEIT::setLebensprozent(void)
{
	char szQuery[200];
	
	sprintf(szQuery, "UPDATE t_einheit SET Lebenprozent = %lf WHERE ID_Einheit = %d",  (this->dLebenProzent), this->iID_Einheit);
	this->pDB->query(szQuery);
}
