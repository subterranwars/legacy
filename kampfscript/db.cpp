/*db.cpp
Diese Klasse integriert die vom Header gelieferten Deklarationen und 
stellt diese Funktionen dann zur Verfügung

History:
			07.09.2004	MvR		created
*/

#include "db.h"

/*Standardkonstruktor*/
DATENBANK::DATENBANK(const char *psHost, int port, const char *psUser, const char *psPasswort, const char *psDatenbank)
{
	this->iShowErrors = 0;

	//Datenbankverbindung herstellen
	mysql_init(&this->dbHandle);
	if (!mysql_real_connect(&this->dbHandle, psHost, psUser, psPasswort, psDatenbank,3307,NULL,0))
	{
		this->debug("Konnte Datenbankverbindung nicht herstellen");
	}
}

/*Dekonstruktor*/
DATENBANK::~DATENBANK()
{
}
			
/*Funktion führt Query aus*/
void DATENBANK::query(char *psQuery)
{
	//Erfolg beim Query?
	if( 0 != mysql_query(&this->dbHandle, psQuery) )
	{
		this->debug("Fehler in Query");
	}
	else
	{
		//Kein Fehler selektiere Query!
		this->dbResult = mysql_store_result(&this->dbHandle);
		
		//ISt Fehler beim Ergebnis-Selektieren aufgetreten?
		if( this->dbResult == NULL )
		{
			this->debug("Problem mit Result");
		}	
		else	//Kein Fehler => Ergebnis kann ausgelesen werden
		{
		}
	} 
}

/*schliesst db-verbindung*/
void DATENBANK::db_close(void)
{
	mysql_close(&this->dbHandle);
}

unsigned int DATENBANK::num_fields(void)
{
	return mysql_num_fields(this->dbResult);
}

void DATENBANK::free_result(void)
{
	mysql_free_result(this->dbResult);
}

/*Debug-_Funktion gibt bei gesetztem iShowErrors-Wert fehlermeldungen aus!*/
void DATENBANK::debug(char *psString)
{


	if( this->iShowErrors == 1 )
	{
		fprintf(stderr, "###Fehler!###\n");
		fprintf(stderr, "%s\n", psString);
		fprintf(stderr, "Error: %s\n", mysql_error(&this->dbHandle));

	}
}

int DATENBANK::insert_id(void)
{
	return mysql_insert_id(&this->dbHandle);
}

int DATENBANK::num_rows(void)
{
	return mysql_num_rows(this->dbResult);
}

