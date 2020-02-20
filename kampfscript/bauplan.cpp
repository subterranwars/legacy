/*bauplan.cpp
 * 
 * History:
 * 		07.09.2004	mvr		created
 * */

#include "bauplan.h"

BAUPLAN::BAUPLAN()
{	
	//Wichtige Daten setzen!
	//this->pDB = new DATENBANK();
}

int BAUPLAN::getID(void)
{
	return this->iID;
}

void BAUPLAN::setzeDatenbank(DATENBANK *pDB)
{
	//Mysql Datenbanverbindung setzen
	this->pDB = pDB;
}

void BAUPLAN::loadBauplan(int iID_User, int iID_Profil)
{
	//Deklarationen
	char szQuery[210];
	MYSQL_ROW dbRow;

	this->iID = iID_Profil;
	this->iID_User = iID_User;
	
	//Lade nun Bauplandaten!
	sprintf(szQuery, "SELECT ChassisTyp, Wendigkeit, Lebenspunkte, Angriff, Panzerung, Zielen, Waffentyp, WaffenbonusTyp, Waffenbonus, BonustypChassis, ChassisBonus, Panzertyp, Panzerbonus FROM t_profil WHERE ID_Profil = %d;\0", this->iID);
	
	pDB->query(szQuery);
	
	//Werte setzen!
	dbRow = mysql_fetch_row(this->pDB->dbResult);
	this->iChassisTyp 		= atoi(dbRow[0]);
	this->iWendigkeit 		= atoi(dbRow[1]);
	this->iLebenspunkte 	= atoi(dbRow[2]);
	this->iAngriff 			= atoi(dbRow[3]);
	this->iPanzerung 		= atoi(dbRow[4]);
	this->iZielen 			= atoi(dbRow[5]);
	this->iWaffenTyp 		= atoi(dbRow[6]);
	this->iBonus_WaffenTyp 	= atoi(dbRow[7]);
	this->dBonus_Waffe 		= atof(dbRow[8]);
	this->iBonus_ChassisTyp = atoi(dbRow[9]);
	this->dBonus_Chassis 	= atof(dbRow[10]);
	this->iPanzerTyp 		= atoi(dbRow[11]);
	this->dBonus_Panzer 	= atof(dbRow[12]);
}

BAUPLAN::~BAUPLAN()
{
	
}

int BAUPLAN::getAngriff(void)
{
	return this->iAngriff;
}

int BAUPLAN::getChassis(void)
{
	return this->iChassisTyp;
}

int BAUPLAN::getChassisBonusTyp(void)
{
	return this->iBonus_ChassisTyp;
}

double BAUPLAN::getChassisBonus(void)
{
	return this->dBonus_Chassis;
}

int BAUPLAN::getPanzerung(void)
{
	return this->iPanzerung;
}

double BAUPLAN::getPanzerBonus(void)
{
	return this->dBonus_Panzer;
}

int BAUPLAN::getPanzerTyp(void)
{
	return this->iPanzerTyp;
}
		
int BAUPLAN::getWendigkeit(void)
{
	return this->iWendigkeit;
}

int BAUPLAN::getZielen(void)
{
	return this->iZielen;
}

int BAUPLAN::getWaffenTyp(void)
{
	return this->iWaffenTyp;
}

int BAUPLAN::getWaffenBonusTyp(void)
{
	return this->iBonus_WaffenTyp;
}

double BAUPLAN::getWaffenBonus(void)
{
	return this->dBonus_Waffe;
}
